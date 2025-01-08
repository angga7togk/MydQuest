<?php

namespace angga7togk\mydquest\database;

use angga7togk\mydquest\database\model\QuestPlayer;
use angga7togk\mydquest\database\storer\SQLDataStorer;
use angga7togk\mydquest\MydQuest;
use angga7togk\mydquest\utils\Cache;
use angga7togk\mydquest\utils\Utils;
use pocketmine\player\Player;

class DataManager
{
  /** Cache constans */
  const CACHE_KEY_PLAYER = "data_manager_player_%s_%s";
  const CACHE_KEY_PLAYERS = "data_manager_players_%s";
  const CACHE_KEY_INIT_PLAYER = "data_manager_init_player_%s";
  const CACHE_KEY_PROGRESS = "data_manager_progress_%s_%s";

  const CACHE_EXPIRE_TIME = 60 * 3; // 3 minute
  const CACHE_EXPIRE_LONG_TIME = 60 * 60 * 24; // 24 hour

  public function __construct(
    private MydQuest $plugin,
    private SQLDataStorer $database
  ) {}

  public function getQuestPlayer(Player $player, string $questId, bool $noCache = false): ?QuestPlayer
  {
    $playerName = strtolower($player->getName());
    $cacheKey = sprintf(self::CACHE_KEY_PLAYER, $playerName, $questId);
    if (Cache::has($cacheKey) && !$noCache) {
      return Cache::get($cacheKey);
    }
    $questPlayer = $this->database->getPlayerOne($player, $questId);
    if ($questPlayer !== null) {
      Cache::set($cacheKey, $questPlayer, self::CACHE_EXPIRE_TIME);
    }
    return $questPlayer;
  }

  /** @return QuestPlayer[] */
  public function getQuestPlayers(Player $player, bool $noCache = false): array
  {
    $playerName = strtolower($player->getName());
    $cacheKey = sprintf(self::CACHE_KEY_PLAYERS, $playerName);
    if (Cache::has($cacheKey) && !$noCache) {
      return Cache::get($cacheKey);
    }
    $questPlayers = $this->database->getPlayerAll($player);
    if ($questPlayers !== null) {
      Cache::set($cacheKey, $questPlayers, self::CACHE_EXPIRE_TIME);
    }
    return $questPlayers;
  }

  public function initDataPlayer(Player $player): void
  {

    $playerName = strtolower($player->getName());
    $cacheKey = sprintf(self::CACHE_KEY_INIT_PLAYER, $playerName);
    if (Cache::has($cacheKey)) {
      return;
    }

    foreach (array_keys($this->plugin->getQuests()) as $id) {
      $this->database->insertPlayer($player, $id, Utils::getDate());
    }
    Cache::set($cacheKey, true, self::CACHE_EXPIRE_LONG_TIME);
  }

  public function setComplete(Player $player, string $questId, bool $isComplete): void
  {
    $this->database->setIsComplete($player, $questId, $isComplete);
  }

  public function setActive(Player $player, string $questId, bool $isActive): void
  {
    $this->database->setIsActive($player, $questId, $isActive);
  }

  public function addCompletedCount(Player $player, string $questId, int $count): void
  {
    $this->database->addCompletedCount($player, $questId, $count);
  }

  public function addFailedCount(Player $player, string $questId, int $count): void
  {
    $this->database->addFailedCount($player, $questId, $count);
  }

  public function addProgress(Player $player, string $questId, int $progress): int
  {
    $cacheKey = sprintf(self::CACHE_KEY_PROGRESS, $player->getName(), $questId);
    $questPlayer = $this->getQuestPlayer($player, $questId);
    
    $currentProgress = Cache::has($cacheKey) ? Cache::get($cacheKey) : $questPlayer->getProgress();
    $goalProgress = $this->plugin->getQuest($questId)->getGoalProgress();

    $currentProgress += $progress;

    $threshold = 10;
    $currentThresholdProgress = $currentProgress % $threshold; // hasil akan 0 - 10

    if (
      $currentThresholdProgress >= $threshold || // jika threshold sekarang di atas 10 maka ...
      $currentProgress >= $goalProgress // jika progress sekarang di atas goal progress maka ...
    ) {
      $this->database->addProgress($player, $questId, $currentProgress);
    }

    Cache::set($cacheKey, $currentProgress, self::CACHE_EXPIRE_LONG_TIME);
    return $currentProgress;
  }

  public function resetPlayerProgress(Player $player): void
  {
    $this->database->resetPlayerProgress($player);
  }
}
