<?php

namespace angga7togk\mydquest\database\storer;

use angga7togk\mydquest\MydQuest;
use angga7togk\mydquest\database\model\QuestPlayer;
use DateTime;
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

  protected abstract function insertPlayer(Player $player, string $questId, DateTime $lastTime): void;

  protected abstract function setIsComplete(Player $player, string $questId, bool $isComplete): void;

  protected abstract function setIsActive(Player $player, string $questId, bool $isActive): void;

  protected abstract function addCompletedCount(Player $player, string $questId, int $count): void;

  protected abstract function addFailedCount(Player $player, string $questId, int $count): void;

  protected abstract function addProgress(Player $player, string $questId, int $progress): void;


  /**
   * Reset data progress player
   */
  protected abstract function resetPlayerProgress(Player $player): void;


  /**
   * Called during class construction to let
   * databases create and initialize their
   * instances.
   */
  protected abstract function prepare(): void;

  protected abstract function close(): void;
}
