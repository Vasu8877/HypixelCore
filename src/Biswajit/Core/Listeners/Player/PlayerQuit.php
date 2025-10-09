<?php

declare(strict_types = 1);

namespace Biswajit\Core\Listeners\Player;

use Biswajit\Core\API;
use Biswajit\Core\Managers\BankManager;
use Biswajit\Core\Player;
use Biswajit\Core\Skyblock;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Server;

class PlayerQuit implements Listener {

    public function onJoin(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
        $name = $player->getName();

        if (!$player instanceof Player) return;

        $event->setQuitMessage(str_replace("{player}", $name, API::getMessage("Quit")));
        $player->save();

        //To Fix The Chunk Load Error!!
        $hub = Server::getInstance()->getWorldManager()->getWorldByName(Skyblock::getInstance()->getConfig()->get("HUB"));
        $player->teleport($hub->getSafeSpawn());
        
        if(isset(BankManager::$interest[$player->getName()])) {
          if(!array_key_exists($player->getName(), BankManager::$interest)) {
            BankManager::$interest[$player->getName()]->cancel();
          } 
       }
    }
}