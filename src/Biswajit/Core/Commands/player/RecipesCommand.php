<?php

namespace Biswajit\Core\Commands\player;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Biswajit\Core\Menus\Recipes;

class RecipesCommand extends Command
{
    public function __construct()
    {
        parent::__construct("recipes", "§eSee Custom Recipes");
        $this->setPermission("normal.cmd");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): mixed
    {
        if ($sender instanceof Player) {
            $sender->sendForm(new RecipesForm());
            return true;
        }
        return false;
    }
}


