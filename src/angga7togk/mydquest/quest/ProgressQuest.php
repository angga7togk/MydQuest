<?php

namespace angga7togk\mydquest\quest;

use angga7togk\mydquest\database\model\QuestPlayer;
use angga7togk\mydquest\MydQuest;
use angga7togk\mydquest\quest\reward\RewardType;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\player\Player;
use pocketmine\world\sound\XpLevelUpSound;

class ProgressQuest
{
  public function __construct(
    private QuestPlayer $questPlayer,
    private Quest $quest,
    private MydQuest $loader,
  ) {}

  public function getQuestPlayer(): QuestPlayer
  {
    return $this->questPlayer;
  }

  public function getQuest(): Quest
  {
    return $this->quest;
  }

  public function getLoader(): MydQuest
  {
    return $this->loader;
  }

  public function runProgress(Player $player, int $addProgress): void
  {
    $db = $this->getLoader()->getDatabase();
    $nextProgress = $this->getQuestPlayer()->getProgress() + $addProgress;
    $db->addProgress($player, $this->getQuest()->getId(), $addProgress);
    if ($nextProgress >= $this->getQuest()->getGoalProgress()) {
      $this->giveRewards($player);
      $player->getWorld()->addSound($player->getPosition(), new XpLevelUpSound(30), [$player]);
      $db->setIsComplete($player, $this->getQuest()->getId(), true);
      $db->addCompletedCount($player, $this->getQuest()->getId(), 1);
    }
  }

  private function giveRewards(Player $player)
  {
    $server = $this->getLoader()->getServer();
    foreach ($this->getQuest()->getRewards() as $reward)  {
      switch ($reward->getType()) {
        case RewardType::COMMAND:
          $server->getCommandMap()->dispatch(new ConsoleCommandSender($server, $server->getLanguage()), str_replace("{player}", $player->getName(), $reward->getValue()));
          break;
        case RewardType::ITEM:
          $player->getInventory()->addItem($reward->getValue());
          break;
        case RewardType::MONEY:
          if (($eco = $this->getLoader()->getEconomyProvider()) !== null) {
            $eco->giveMoney($player, $reward->getValue());
          }
          break;
        case RewardType::XP:
          $player->getXpManager()->addXp($reward->getValue());
          break;
        case RewardType::XP_LEVEL:
          $player->getXpManager()->addXpLevels($reward->getValue());
          break;
      }
    }
  }
}
