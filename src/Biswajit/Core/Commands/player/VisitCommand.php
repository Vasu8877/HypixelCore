<?php

namespace Biswajit\Core\Commands\player;

use Biswajit\Core\Managers\IslandManager;
use Biswajit\Core\Menus\island\IslandVisitAllOpenForm;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class VisitCommand extends Command
{
    public function __construct()
    {
        parent::__construct("visit", "§bVisit Player Island", "/visit {player name}");
        $this->setPermission("island.cmd");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): mixed
    {
        if ($sender instanceof Player) {
            if (isset($args[0])) {
                IslandManager::islandVisit($sender, $args[0]);
                return true;
            }
            $sender->sendForm(new IslandVisitAllOpenForm());
        }
        return false;
    }
}
