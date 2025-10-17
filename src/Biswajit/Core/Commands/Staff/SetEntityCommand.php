<?php

declare(strict_types=1);

namespace Biswajit\Core\Commands\Staff;

use Biswajit\Core\Skyblock;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class SetEntityCommand extends Command
{
    private const ENTITIES = [
        'zombie', 'creeper', 'skeleton', 'spider', 'pig', 'sheep', 'cow', 'chicken'
    ];

    public function __construct()
    {
        parent::__construct("set", "Set entity spawn point", "/set <entity>", ["setentity"]);
        $this->setPermission("staff.entity.cmd");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command can only be used in-game!");
            return false;
        }

        if (!$this->testPermission($sender)) {
            $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command!");
            return false;
        }

        if (count($args) !== 1) {
            $sender->sendMessage(TextFormat::YELLOW . "Usage: /set <entity>");
            $sender->sendMessage(TextFormat::GRAY . "Available entities: " . implode(", ", self::ENTITIES));
            return false;
        }

        $entityType = strtolower($args[0]);
        if (!in_array($entityType, self::ENTITIES)) {
            $sender->sendMessage(TextFormat::RED . "Invalid entity type! Available: " . implode(", ", self::ENTITIES));
            return false;
        }

        $config = new Config(Skyblock::getInstance()->getDataFolder() . "entity.yml", Config::YAML, []);
        $entities = $config->getAll();

        $position = $sender->getPosition();
        $worldName = $sender->getWorld()->getFolderName();

        if (!isset($entities[$entityType])) {
            $entities[$entityType] = [];
        }

        $entities[$entityType][] = [
            'world' => $worldName,
            'x' => $position->getX(),
            'y' => $position->getY(),
            'z' => $position->getZ()
        ];

        $config->setAll($entities);
        $config->save();

        $sender->sendMessage(TextFormat::GREEN . "Set spawn point for " . ucfirst($entityType) . " at " .
            "X: " . round($position->getX(), 2) . ", Y: " . round($position->getY(), 2) . ", Z: " . round($position->getZ(), 2) .
            " in world: " . $worldName);

        return true;
    }
}
