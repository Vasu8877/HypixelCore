<?php

declare(strict_types = 1);

namespace Biswajit\Core;

use Biswajit\Core\Sessions\SessionsData;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player as PMMPPlayer;

class Player extends PMMPPlayer {

    use SessionsData;

    protected function initEntity(CompoundTag $nbt) : void {

		parent::initEntity($nbt);
    }
}