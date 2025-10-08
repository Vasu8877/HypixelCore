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
    $this->loadData();
    }

    public function damagePlayer(float $amount): void {

      $this->setHealth($this->getHealth() - $amount);
      $this->doHitAnimation();

      if($this->getHealth() <= 0) {
         $world = $this->getWorld();
		  	 $this->teleport($world->getSpawnLocation());
         $this->setHealth($this->getMaxHealth());
      }
    }
}