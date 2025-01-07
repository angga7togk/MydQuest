<?php

namespace angga7togk\mydquest\quest;

use angga7togk\mydquest\quest\Chance;
use angga7togk\mydquest\reward\BaseReward;
use pocketmine\event\Listener;

class BaseQuest implements Listener
{

  /**
   * @param string $questId max 6 characters
   * @param string $name max 32 characters
   * @param string $description
   * @param Difficulty $difficulty
   * @param BaseReward[] $rewards
   */
  public function __construct(
    public string $questId,
    public string $name,
    public string $description,
    public Difficulty $difficulty,
    public array $rewards,
  ) {
    if (strlen($questId) > 6) {
      throw new \InvalidArgumentException("Quest ID must be less than 6 characters.");
    }
    if (strlen($name) > 32) {
      throw new \InvalidArgumentException("Quest name must be less than 32 characters.");
    }
    foreach ($rewards as $reward) {
      if (!$reward instanceof BaseReward) {
        throw new \InvalidArgumentException("Reward must be an instance of BaseReward.");
      }
    }
  }
}
