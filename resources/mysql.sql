-- #! mysql
-- #{ skyblockIsland

-- # { init
CREATE TABLE IF NOT EXISTS skyblockIsland (
    player VARCHAR(255) PRIMARY KEY,
    data TEXT NOT NULL
);
-- # }

-- # { getIslandData
-- #   :player string
SELECT data FROM skyblockIsland WHERE player=:player;
-- # }

-- # { saveIslandData
-- #   :player string
-- #   :data string
INSERT INTO skyblockIsland (player, data) VALUES (:player, :data)
ON DUPLICATE KEY UPDATE data=:data;
-- # }

-- # { deleteIslandData
-- #   :player string
DELETE FROM skyblockIsland WHERE player=:player;
-- # }

-- # }
