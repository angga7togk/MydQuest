<?php

namespace angga7togk\mydquest\quest\reward\types;

use angga7togk\mydquest\quest\reward\Reward;

class MoneyReward extends Reward
{

  public function getValue(): int
  {
    return (int)$this->value;
  }
}
