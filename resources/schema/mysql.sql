-- #!mysql
-- #{ mydquest

-- #  { init
CREATE TABLE
  IF NOT EXISTS MydQuest (
    Player VARCHAR(16) NOT NULL,
    QuestId VARCHAR(6) NOT NULL,
    QuestProgress INT NOT NULL DEFAULT 0,
    IsComplete BOOLEAN NOT NULL DEFAULT false,
    IsActive BOOLEAN NOT NULL DEFAULT false,
    CompletedCount INT NOT NULL DEFAULT 0,
    FailedCount INT NOT NULL DEFAULT 0,
    LastTime TIMESTAMP NOT NULL,
    PRIMARY KEY (Player, QuestId)
  );
-- #  }

-- #  { insert_player
-- #    :player string
-- #    :questid string
-- #    :lasttime timestamp
INSERT INTO MydQuest (
    Player,
    QuestId,
    LastTime
  )
VALUES (
    :player,
    :questid,
    :lasttime
  );
-- #  }

-- #  { getplayer_all
-- #    :player string
SELECT * FROM MydQuest WHERE Player = :player;
-- #  }

-- #  { getplayer_one
-- #    :player string
-- #    :questid string
SELECT * FROM MydQuest WHERE Player = :player AND QuestId = :questid;
-- #  }

-- #  { set_is_complete
-- #    :player string
-- #    :questid string
-- #    :value boolean
UPDATE MydQuest
SET IsComplete = :value
WHERE Player = :player AND QuestId = :questid;
-- #  }

-- #  { set_is_active
-- #    :player string
-- #    :questid string
-- #    :value boolean
UPDATE MydQuest
SET IsActive = :value
WHERE Player = :player AND QuestId = :questid;
-- #  }

-- #  { set_completed_count
-- #    :player string
-- #    :questid string
-- #    :value int
UPDATE MydQuest
SET CompletedCount = :value
WHERE Player = :player AND QuestId = :questid;
-- #  }

-- #  { add_completed_count
-- #    :player string
-- #    :questid string
-- #    :value int
UPDATE MydQuest
SET CompletedCount = CompletedCount + :value
WHERE Player = :player AND QuestId = :questid;
-- #  }

-- #  { set_failed_count
-- #    :player string
-- #    :questid string
-- #    :value int
UPDATE MydQuest
SET FailedCount = :value
WHERE Player = :player AND QuestId = :questid;
-- #  }

-- #  { add_failed_count
-- #    :player string
-- #    :questid string
-- #    :value int
UPDATE MydQuest
SET FailedCount = FailedCount + :value
WHERE Player = :player AND QuestId = :questid;
-- #  }

-- #  { set_progress
-- #    :player string
-- #    :questid string
-- #    :value int
UPDATE MydQuest
SET QuestProgress = :value
WHERE Player = :player AND QuestId = :questid;
-- #  }

-- #  { add_progress
-- #    :player string
-- #    :questid string
-- #    :value int
UPDATE MydQuest
SET QuestProgress = QuestProgress + :value
WHERE Player = :player AND QuestId = :questid;
-- #  }

-- #  { set_last_time
-- #    :player string
-- #    :questid string
-- #    :value string
UPDATE MydQuest
SET LastTime = LastTime + :value
WHERE Player = :player AND QuestId = :questid;
-- #  }

-- #  { reset_player_progress
-- #    :player string
UPDATE MydQuest
SET IsComplete = false,
    IsActive = false,
    QuestProgress = 0
WHERE Player = :playere;
-- #  }

-- #}