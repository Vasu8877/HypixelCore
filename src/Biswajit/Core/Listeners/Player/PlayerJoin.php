<?php

declare(strict_types = 1);

namespace Biswajit\Core\Listeners\Player;

use Biswajit\Core\API;
use Biswajit\Core\Managers\BankManager;
use Biswajit\Core\Player;
use Biswajit\Core\Skyblock;
use Biswajit\Core\Tasks\InterestTask;
use Biswajit\Core\Utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class PlayerJoin implements Listener {

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $name = $player->getName();

		if(!$player instanceof Player) return;

        $event->setJoinMessage(str_replace("{player}", $name, API::getMessage("Join")));

        $player->getInventory()->setItem(8, API::getItem("menu"));
        $servername = Utils::getServerName();
        $player->sendMessage(str_replace(["{player}", "{servername}", "{vote}", "{discord}"], [$name, $servername, Skyblock::getInstance()->getConfig()->get("VOTE-WEBSITE"), Skyblock::getInstance()->getConfig()->get("DISCORD-LINK")], API::getMessage("Join-Message")));
 
        if (BankManager::getBankMoney($player) > 0) {
          if(!array_key_exists($player->getName(), BankManager::$interest)) {
            BankManager::$interest[$player->getName()] =  Skyblock::getInstance()->getScheduler()->scheduleRepeatingTask(new InterestTask(Skyblock::getInstance(), $player), 72000);
      } 
    }
  }
}