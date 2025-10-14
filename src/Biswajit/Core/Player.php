<?php

declare(strict_types = 1);

namespace Biswajit\Core;

use Biswajit\Core\Sessions\EconomySession;
use Biswajit\Core\Sessions\SessionsData;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player as PMMPPlayer;

class Player extends PMMPPlayer {

    use SessionsData;
    use EconomySession;

    protected function initEntity(CompoundTag $nbt) : void {
		parent::initEntity($nbt);
        $this->Load();
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

    public function Load(): void {
      $this->loadData();
      $this->loadEconomy();
    }

    public function save(): void {
      $this->saveData();
      $this->saveEconomy();
    }
}