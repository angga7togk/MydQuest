<?php

namespace angga7togk\mydquest\database\model;

use DateTime;

final class QuestPlayer
{

  public function __construct(
    private readonly string $playerName,
    private readonly string $questId,
    private readonly int $progress,
    private readonly bool $isComplete,
    private readonly bool $isActive,
    private readonly int $completedCount,
    private readonly int $failedCount,
    private readonly string $lastTime,
  ) {}

  public function getPlayerName(): string
  {
    return $this->playerName;
  }

  public function getQuestId(): string
  {
    return $this->questId;
  }
  
  public function getProgress(): int
  {
    return $this->progress;
  }

  public function isComplete(): bool
  {
    return $this->isComplete;
  }

  public function isActive(): bool
  {
    return $this->isActive;
  }

  public function getCompletedCount(): int
  {
    return $this->completedCount;
  }

  public function getFailedCount(): int
  {
    return $this->failedCount;
  }

  public function getLastTime(): DateTime {
    return new DateTime($this->lastTime);
  }
}
