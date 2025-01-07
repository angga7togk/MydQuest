<?php

namespace angga7togk\mydquest\reward\types;

use angga7togk\mydquest\reward\Reward;

class MoneyReward extends Reward
{

  public function getValue(): int
  {
    return (int)$this->value;
  }
}
