<?php

declare(strict_types=1);

namespace Biswajit\Core\Listeners\Player;

use Biswajit\Core\Managers\RankManager;
use Biswajit\Core\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\player\chat\LegacyRawChatFormatter;

class PlayerChat implements Listener {

    public function onPlayerChat(PlayerChatEvent $event) {
      $msg = $event->getMessage();
      $player = $event->getPlayer();
    
      if(!$player instanceof Player) return;
 
      $format = RankManager::getChatFormat(RankManager::getRankOfPlayer($player));
      $finalMessage = str_replace(["&", "{player_name}", "{msg}"], ["§", $player->getName(), $msg], $format);
      $event->setFormatter(new LegacyRawChatFormatter($finalMessage));
    }
 }