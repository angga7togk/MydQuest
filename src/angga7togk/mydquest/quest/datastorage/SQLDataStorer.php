<?php

namespace angga7togk\mydquest\quest\datastorage;


use angga7togk\mydquest\quest\datastorage\model\QuestPlayer;
use pocketmine\player\Player;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;

class SQLDataStorer extends BaseDataStorer
{

  const INITIALIZE_TABLES = "mydquest.init";

  const GET_PLAYER_ALL = "mydquest.getplayer_all";
  const GET_PLAYER_ONE = "mydquest.getplayer_one";

  const SET_IS_COMPLETE = "mydquest.set_is_complete";
  const SET_IS_ACTIVE = "mydquest.set_is_active";
  const SET_COMPLETED_COUNT = "mydquest.set_completed_count";
  const SET_FAILED_COUNT = "mydquest.set_failed_count";
  const SET_PROGRESS = "mydquest.set_progress";

  const ADD_COMPLETED_COUNT = "mydquest.add_completed_count";
  const ADD_FAILED_COUNT = "mydquest.add_failed_count";
  const ADD_PROGRESS = "mydquest.add_progress";

  const RESET_PLAYER_PROGRESS = "mydquest.reset_player_progess";

  protected DataConnector $database;
  protected string $type;

  public function getPlayerAll(Player $player): array
  {
    $playerName = strtolower($player->getName());
    $questPlayers = [];

    $this->database->executeSelect(self::GET_PLAYER_ALL, [
      'player' => $playerName,
    ], function (array $rows) use (&$questPlayers, $playerName) {
      foreach ($rows as $row) {
        $questPlayers[] = new QuestPlayer(
          $playerName,
          $row['QuestId'],
          (int)$row['QuestProgress'],
          (bool)$row['IsComplete'],
          (bool)$row['IsActive'],
          (int)$row['CompletedCount'],
          (int)$row['FailedCount']
        );
      }
    });

    // Menunggu hasil async selesai (opsional, tergantung pada implementasi lingkungan)
    $this->database->waitAll();

    return $questPlayers;
  }

  public function getPlayerOne(Player $player, string $questId): ?QuestPlayer
  {
    $playerName = strtolower($player->getName());

    $result = null;

    $this->database->executeSelect(self::GET_PLAYER_ONE, [
      'player' => $playerName,
      'questid' => $questId,
    ], function (array $rows) use (&$result) {
      if (!empty($rows)) {
        $row = $rows[0];
        $result = new QuestPlayer(
          $row['Player'],
          $row['QuestId'],
          $row['QuestProgress'],
          (bool) $row['IsComplete'],
          (bool) $row['IsActive'],
          $row['CompletedCount'],
          $row['FailedCount']
        );
      }
    });

    return $result;
  }


  public function setIsComplete(Player $player, string $questId, bool $isComplete): void
  {
    $this->database->executeChange(self::SET_IS_COMPLETE, [
      'player' => strtolower($player->getName()),
      'questid' => $questId,
      'value' => $isComplete,
    ]);
  }

  public function setIsActive(Player $player, string $questId, bool $isActive): void
  {
    $this->database->executeChange(self::SET_IS_ACTIVE, [
      'player' => strtolower($player->getName()),
      'questid' => $questId,
      'value' => $isActive,
    ]);
  }

  public function setCompletedCount(Player $player, string $questId, int $count): void
  {
    $this->database->executeChange(self::SET_COMPLETED_COUNT, [
      'player' => strtolower($player->getName()),
      'questid' => $questId,
      'value' => $count,
    ]);
  }

  public function addCompletedCount(Player $player, string $questId, int $count): void
  {
    $this->database->executeChange(self::ADD_COMPLETED_COUNT, [
      'player' => strtolower($player->getName()),
      'questid' => $questId,
      'value' => $count,
    ]);
  }

  public function setFailedCount(Player $player, string $questId, int $count): void
  {
    $this->database->executeChange(self::SET_FAILED_COUNT, [
      'player' => strtolower($player->getName()),
      'questid' => $questId,
      'value' => $count,
    ]);
  }

  public function addFailedCount(Player $player, string $questId, int $count): void
  {
    $this->database->executeChange(self::ADD_FAILED_COUNT, [
      'player' => strtolower($player->getName()),
      'questid' => $questId,
      'value' => $count,
    ]);
  }

  public function setProgress(Player $player, string $questId, int $progress): void
  {
    $this->database->executeChange(self::SET_PROGRESS, [
      'player' => strtolower($player->getName()),
      'questid' => $questId,
      'value' => $progress,
    ]);
  }

  public function addProgress(Player $player, string $questId, int $progress): void
  {
    $this->database->executeChange(self::ADD_PROGRESS, [
      'player' => strtolower($player->getName()),
      'questid' => $questId,
      'value' => $progress,
    ]);
  }

  public function reset(Player $player): void
  {
    $this->database->executeChange(self::RESET_PLAYER_PROGRESS, [
      'player' => strtolower($player->getName())
    ]);
  }


  public function prepare(): void
  {
    $pl = $this->getPlugin();

    $database_schema = $pl->getDataFolder() . "schema/";
    if (!is_dir($database_schema)) {
      mkdir($database_schema);
    }
    $pl->saveResource("schema/mysql.sql", true);
    $pl->saveResource("schema/sqlite.sql", true);

    $db = $pl->getConfig()->getAll()['database'];
    $this->type = $db['type'];
    $mysql = $db['mysql'];
    $libasynql_config = [
      "type" => $this->type,
      "sqlite" => [
        "file" => $pl->getDataFolder() . "mydquest.sqlite3"
      ],
      "mysql" => array_combine(
        ["host", "username", "password", "schema", "port"],
        [$mysql["host"], $mysql["username"], $mysql["password"], $mysql["database"], $mysql["port"]]
      )
    ];
    $this->database = libasynql::create($pl, $libasynql_config, [
      "mysql" => "schema/mysql.sql",
      "sqlite" => "schema/sqlite.sql"
    ]);

    $this->database->executeGeneric(self::INITIALIZE_TABLES);
  }

  public function close(): void
  {
    $this->database->close();
  }
}
