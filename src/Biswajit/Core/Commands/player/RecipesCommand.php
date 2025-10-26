<?php

namespace Pushkar\MagicCore\commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Pushkar\MagicCore\forms\RecipesForm;

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
