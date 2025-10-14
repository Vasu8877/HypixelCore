<?php

namespace Biswajit\Core\Entitys\Minion;

use pocketmine\item\Item;

interface MinionInterface {

    public function onTick(): void;
    public function setUp(): void;
	public function getEgg(): Item;
}