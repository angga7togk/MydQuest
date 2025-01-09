<?php

namespace angga7togk\mydquest\database\storer;

use angga7togk\mydquest\utils\Utils;
use pocketmine\player\Player;

class SQLiteDataStorer extends SQLDataStorer
{

  public function insertPlayer(Player $player, string $questId): void
  {
    $this->database->executeChange(self::INSERT_PLAYER, [
      'player' => strtolower($player->getName()),
      'questid' => $questId,
      'lasttime' => Utils::getDate()->format('Y-m-d H:i:s'),
    ]);
  }

  public function resetPlayerProgress(Player $player): void
  {
    $this->database->executeChange(self::RESET_PLAYER_PROGRESS, [
      'player' => strtolower($player->getName()),
      'lasttime' => Utils::getDate()->format('Y-m-d H:i:s')
    ]);
  }
}
