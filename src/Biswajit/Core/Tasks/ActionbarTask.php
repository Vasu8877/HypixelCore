<?php

namespace Biswajit\Core\Tasks;

use Biswajit\Core\Player;
use Biswajit\Core\Skyblock;
use pocketmine\scheduler\Task;

class ActionbarTask extends Task
{
    public function onRun(): void
    {
        foreach (Skyblock::getInstance()->getServer()->getOnlinePlayers() as $player) {
            if ($player instanceof Player) {
                $mana = $player->getMana();
                $maxMana = $player->getMaxMana();
                $heal = $player->getHealth();
                $maxheal = $player->getMaxHealth();

                if ($heal > $maxheal) {
                    $player->setHealth($maxheal);
                }
                $player->sendActionBarMessage("§cHealth: $heal" . "§7/§c$maxheal  §bMana: $mana" . "§7/§b$maxMana ");
            }
        }
    }
}