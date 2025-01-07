<?php

namespace angga7togk\mydquest\reward\types;

use angga7togk\mydquest\reward\Reward;

class CommandReward extends Reward
{

  public function getValue(): string
  {
    return (string)$this->value;
  }
}
