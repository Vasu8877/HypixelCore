<?php

declare(strict_types = 1);

namespace Biswajit\Core\Managers;

use Biswajit\Core\Player;

class EconomyManager {
    
    use ManagerBase;

  public static function getMoney(Player $player): float {
   return $player->getEconomy("money") ?? 0;
  }
 
  public static function setMoney(Player $player, float $amount): void {
    $player->setEconomy("money", $amount);
  }

  public static function addMoney(Player $player, float $amount): void {
    $player->setEconomy("money", self::getMoney($player) + $amount);
  }
  
  public static function subtractMoney(Player $player, float $amount): void {
    $player->setEconomy("money", self::getMoney($player) - $amount);
  }
}