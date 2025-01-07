<?php

namespace angga7togk\mydquest\quest\datastorage;

use angga7togk\mydquest\MydQuest;
use angga7togk\mydquest\quest\datastorage\model\QuestPlayer;
use pocketmine\player\Player;

abstract class BaseDataStorer
{

  public function __construct(protected MydQuest $plugin)
  {
    $this->prepare();
  }

  public function getPlugin(): MydQuest
  {
    return $this->plugin;
  }

  /**
   * @return QuestPlayer[]
   */
  protected abstract function getPlayerAll(Player $player): array;

  protected abstract function getPlayerOne(Player $player, string $questId): ?QuestPlayer;

  protected abstract function setIsComplete(Player $player, string $questId, bool $isComplete): void;

  protected abstract function setIsActive(Player $player, string $questId, bool $isActive): void;

  protected abstract function setCompletedCount(Player $player, string $questId, int $count): void;
  protected abstract function addCompletedCount(Player $player, string $questId, int $count): void;

  protected abstract function setFailedCount(Player $player, string $questId, int $count): void;
  protected abstract function addFailedCount(Player $player, string $questId, int $count): void;

  protected abstract function setProgress(Player $player, string $questId, int $progress): void;
  protected abstract function addProgress(Player $player, string $questId, int $progress): void;


  /**
   * Reset data progress player if change day
   */
  protected abstract function reset(Player $player): void;

  /**
   * Called during class construction to let
   * databases create and initialize their
   * instances.
   */
  protected abstract function prepare(): void;

  protected abstract function close(): void;
}
