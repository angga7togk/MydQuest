<?php

namespace angga7togk\mydquest\quest\reward\types;

use angga7togk\mydquest\quest\reward\Reward;

class CommandReward extends Reward
{

  public function getValue(): string
  {
    return (string)$this->value;
  }
}
