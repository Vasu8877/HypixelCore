<?php

declare(strict_types = 1);

namespace Biswajit\Core\Listeners\Player;

use Biswajit\Core\API;
use Biswajit\Core\Skyblock;
use Biswajit\Core\Utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Server;

class PlayerJoin implements Listener {

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $name = $player->getName();

        $event->setJoinMessage(str_replace("{player}", $name, API::getMessage("Join")));

        $servername = Utils::getServerName();
        $player->sendMessage(str_replace(["{player}", "{servername}", "{vote}", "{discord}"], [$name, $servername, Skyblock::getInstance()->getConfig()->get("VOTE-WEBSITE"), Skyblock::getInstance()->getConfig()->get("DISCORD-LINK")], API::getMessage("Join-Message")));

        //To Fix The Chunk Load Error!!
        $hub = Server::getInstance()->getWorldManager()->getWorldByName(Skyblock::getInstance()->getConfig()->get("HUB"));
        $player->teleport($hub->getSafeSpawn());
    }
}