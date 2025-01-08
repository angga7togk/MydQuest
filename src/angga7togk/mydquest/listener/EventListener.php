<?php

namespace angga7togk\mydquest\listener;

use angga7togk\mydquest\MydQuest;
use angga7togk\mydquest\utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class EventListener implements Listener
{

  public function __construct(
    private MydQuest $plugin
  ) {}

  public function onJoin(PlayerJoinEvent $event)
  {
    $player = $event->getPlayer();;
    $this->plugin->getDataManager()->initDataPlayer($player);
  }
}
