<?php

namespace angga7togk\mydquest;

use angga7togk\mydquest\i18n\MydLang;
use angga7togk\mydquest\listener\EventListener;
use angga7togk\mydquest\quest\Quest;
use angga7togk\mydquest\quest\datastorage\MySQLDataStorer;
use angga7togk\mydquest\quest\datastorage\SQLDataStorer;
use angga7togk\mydquest\quest\datastorage\SQLiteDataStorer;
use angga7togk\mydquest\quest\Difficulty;
use angga7togk\mydquest\quest\JsonQuest;
use angga7togk\mydquest\utils\Utils;
use DaPigGuy\libPiggyEconomy\libPiggyEconomy;
use DaPigGuy\libPiggyEconomy\providers\EconomyProvider;
use DaPigGuy\libPiggyUpdateChecker\libPiggyUpdateChecker;
use pocketmine\item\StringToItemParser;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class MydQuest extends PluginBase
{

  use SingletonTrait;

  private SQLDataStorer $database;
  private ?EconomyProvider $economyProvider = null;


  public static bool $piggyCustomEnchantmentsSupported = false;

  /** @var array<string, Quest> $quests*/
  private array $quests = [];

  public function onLoad(): void
  {
    self::setInstance($this);
  }

  public function onEnable(): void
  {
    libPiggyUpdateChecker::init($this);
    $this->saveDefaultConfig();
    $this->selectDatabase();
    $this->selectLanguage();

    /** Register Default Quests */
    $this->registerJsonQuests();


    $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);


    $this->checkSoftDependencies();
  }

  public function onDisable(): void
  {
    if (isset($this->database)) $this->database->close();
  }

  private function checkSoftDependencies(): void
  {
    $plMgr = $this->getServer()->getPluginManager();
    
    self::$piggyCustomEnchantmentsSupported = $plMgr->getPlugin("PiggyCustomEnchants") !== null;

    if (
      $plMgr->getPlugin("EconomyAPI") !== null ||
      $plMgr->getPlugin("BedrockEconomy") !== null
    ) {
      /** Init Economy Provider */
      libPiggyEconomy::init();
      $this->economyProvider = libPiggyEconomy::getProvider($this->getConfig()->get("economy"));
    }
  }

  private function selectLanguage(): void
  {

    $oldLanguageDir = $this->getDataFolder() . "language";
    if (file_exists($oldLanguageDir)) {
      Utils::unlinkRecursive($oldLanguageDir);
    }

    $resources = $this->getResources();
    foreach ($resources as $resource) {
      $fileName = $resource->getFileName();
      $extension = Utils::getFileExtension($fileName);

      if ($extension !== MydLang::LANGUAGE_EXTENSION) continue;

      $lang = new MydLang($resource);
      $this->getLogger()->debug("Loaded language file: {$lang->getLang()}.ini");
    }
    MydLang::setConsoleLocale($this->getConfig()->get('language', 'en_us'));
  }

  private function selectDatabase(): bool
  {
    switch (strtolower($this->getConfig()->getNested("database.type", "sqlite3"))) {
      default:
      case "mysql":
        $this->database = new MySQLDataStorer($this);
        break;
      case "sqlite":
      case "sqlite3":
        $this->database = new SQLiteDataStorer($this);
        break;
    }
    return true;
  }

  public function getDatabase(): SQLDataStorer
  {
    return $this->database;
  }

  public function getEconomyProvider(): EconomyProvider
  {
    return $this->economyProvider;
  }

  public function getQuest(string $id): ?Quest
  {
    return $this->quests[$id] ?? null;
  }

  /**
   * @return array<string, Quest>
   */
  public function getQuests(): array
  {
    return $this->quests;
  }

  /**
   * Mendaftarkan semua Quest berbasis JSON yang di dalam folder resources/quests.
   * English: Register all quests based on JSON in the resources/quests folder.
   */
  private function registerJsonQuests(): void
  {
    $questFolder = $this->getDataFolder() . "quests/";
    if (!is_dir($questFolder)) {
      mkdir($questFolder);
    }

    $resourcePath = $this->getFile() . "resources/quests";
    $dirIterator = new \RecursiveDirectoryIterator($resourcePath, \FilesystemIterator::SKIP_DOTS);
    $iterator = new \RecursiveIteratorIterator($dirIterator);

    foreach ($iterator as $file) {
      if ($file->getExtension() === "json" && !str_starts_with($file->getFilename(), "__")) {
        $this->saveResource("quests/" . $file->getFilename());
        $questJsonToArray = (new  Config($questFolder . $file->getFileName(), Config::JSON, []))->getAll();
        $this->registerQuestArray($questJsonToArray);
      }
    }
  }

  /**
   * Mendaftarkan Quest berdasarkan array (Json yang di jadikan array).
   * English: Register quest based on array (Json which is made into an array).
   */
  public function registerQuestArray(array $q): void
  {
    $this->validationQuestArray($q);


    $quest = new JsonQuest(
      $q['id'],
      $q['name'],
      $q['description'],
      Difficulty::tryFrom($q['difficulty']),
      StringToItemParser::getInstance()->parse($q['button']),
      (int)$q['goal_progress'],
      [],
      $q['actions']
    );
    $this->getServer()->getLogger()->debug("Registered quest: {$quest->getId()}");
    $this->quests[$q['id']] = $quest;
    $this->getServer()->getPluginManager()->registerEvents($quest, $this);
  }

  /**
   * Validasi array Quest.
   */
  private function validationQuestArray(array $q): void
  {
    if (!isset($q['id'])) throw new \Exception("Key 'id' is missing in the json quest array.");
    if (!isset($q['name'])) throw new \Exception("Key 'name' is missing in the json quest array.");
    if (!isset($q['description'])) throw new \Exception("Key 'description' is missing in the json quest array.");
    if (!isset($q['button'])) throw new \Exception("Key 'button' is missing in the json quest array.");
    if (!isset($q['difficulty'])) throw new \Exception("Key 'difficulty' is missing in the json quest array.");
    if (!isset($q['goal_progress'])) throw new \Exception("Key 'goal_progress' is missing in the json quest array.");
    if (!isset($q['rewards'])) throw new \Exception("Key 'rewards' is missing in the json quest array.");

    if (strlen($q['id'])  > 6) throw new \Exception("Key 'id' must be less than 6 characters.");
    if (strlen($q['name']) > 32) throw new \Exception("Key 'name' must be less than 32 characters.");

    if (StringToItemParser::getInstance()->parse($q['button']) === null) throw new \Exception("Key 'button' must be a valid item.");

    if (
      $q['difficulty'] !== "EASY" &&
      $q['difficulty'] !== "NORMAL" &&
      $q['difficulty'] !== "HARD" &&
      $q['difficulty'] !== "IMPOSSIBLE"
    ) {
      throw new \Exception("Key 'difficulty' must be 'EASY', 'NORMAL', 'HARD', or 'IMPOSSIBLE'.");
    }

    foreach ($q['rewards'] as $reward) {
      if (!isset($reward['type'])) throw new \Exception("Key 'type' is missing in the 'rewards' json quest array.");
      if (
        $reward['type'] !== 'COMMAND' &&
        $reward['type'] !== 'ITEM' &&
        $reward['type'] !== 'MONEY' &&
        $reward['type'] !== 'XP'
      ) {
      }
      if (!isset($reward['value'])) throw new \Exception("Key 'value' is missing in the 'rewards' json quest array.");
    }
  }
}
