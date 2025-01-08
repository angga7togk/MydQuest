<?php

namespace angga7togk\mydquest\quest\reward;

use angga7togk\mydquest\quest\reward\RewardChance;
use angga7togk\mydquest\quest\reward\RewardType;

abstract class Reward
{

  public function __construct(
    protected RewardType $type,
    protected mixed $value,
    protected ?RewardChance $chance = null,
  ) {}

  public function getType(): RewardType
  {
    return $this->type;
  }

  public function getChance(): RewardChance
  {
    return $this->chance ?? RewardChance::COMMON;
  }

  abstract public function getValue(): mixed;
}
