<?php

namespace angga7togk\mydquest\quest;

use angga7togk\mydquest\i18n\MydLang;
use angga7togk\mydquest\MydQuest;
use angga7togk\mydquest\quest\reward\Reward;
use angga7togk\mydquest\utils\Utils;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class Quest
{

  private MydQuest $loader;
  private MydLang $lang;

  /**
   * @param string $questId max 6 characters
   * @param string $name max 32 characters
   * @param string $description
   * @param Difficulty $difficulty
   * @param Item $button
   * @param int $goal_progress
   * @param Reward[] $rewards
   * @param ?array $actions
   */
  public function __construct(
    private string $questId,
    private string $name,
    private string $description,
    private Difficulty $difficulty,
    private Item $button,
    private int $goal_progress,
    private array $rewards,
    private ?array $actions = null,
  ) {
    $this->validationThrow();
    $this->loader = MydQuest::getInstance();
    $this->lang = MydLang::fromConsole();
  }

  public function getId(): string
  {
    return $this->questId;
  }

  public function getName(): string
  {
    return $this->lang->translateString($this->name);
  }

  public function getDescription(): string
  {
    return $this->lang->translateString($this->description);
  }

  public function getButton(): Item
  {
    return $this->button
      ->setCustomName(TextFormat::colorize($this->name))
      ->setLore(Utils::translateStringToLore($this->description, "&7"));
  }

  public function getDifficulty(): Difficulty
  {
    return $this->difficulty;
  }

  public function getGoalProgress(): int
  {
    return $this->goal_progress;
  }

  /** 
   * @return Reward[]
   */
  public function getRewards(): array
  {
    return $this->rewards;
  }

  public function getActions(): array
  {
    return $this->actions ?? [];
  }

  protected function getLoader(): MydQuest
  {
    return $this->loader;
  }

  protected function getLang(): MydLang
  {
    return $this->lang;
  }

  /** @param callable(ProgressQuest $progress) */
  protected function preRunProgress(Player $player, callable $callable): void
  {
    $db = $this->getLoader()->getDatabase();
    $db->getPlayerOne($player, $this->getId(), function ($questPlayer) use (&$player, &$callable) {
      if ($questPlayer?->isActive() !== true) return;
      if ($questPlayer?->isComplete() === true) return;

      $callable(new ProgressQuest($questPlayer, $this, $this->loader));
    });
  }


  /**
   * Validasi biar ga kelewat batas :V
   */
  private function validationThrow(): void
  {
    if (strlen($this->questId) > 6) {
      throw new \InvalidArgumentException("Quest ID must be less than 6 characters.");
    }
    if (strlen($this->name) > 32) {
      throw new \InvalidArgumentException("Quest name must be less than 32 characters.");
    }
    foreach ($this->rewards as $reward) {
      if (!$reward instanceof Reward) {
        throw new \InvalidArgumentException("Reward must be an instance of Reward.");
      }
    }
  }
}
