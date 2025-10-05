<?php

declare(strict_types = 1);

namespace Biswajit\Core;

use Biswajit\Core\Managers\IslandManager;
use Biswajit\Core\Managers\Worlds\IslandGenerator;
use Biswajit\Core\Utils\Loader;
use Biswajit\Core\Utils\Utils;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;
use pocketmine\world\generator\GeneratorManager;

class Skyblock extends PluginBase {

    use SingletonTrait;
    use Database;

    public static string $prefix;

    public function onLoad(): void {
        self::$instance = $this;
        self::$prefix = Skyblock::getInstance()->getConfig()->get("PREFIX");

        $this->reloadConfig();

        GeneratorManager::getInstance()->addGenerator(IslandGenerator::class, "void", fn() => null, true);
        
        @mkdir($this->getDataFolder() . "island");
        $this->saveResource("HUB.zip");
        $this->saveResource("messages.yml");

        $this->getLogger()->info("§l§bLoading SkyblockCore Version: ". TextFormat::YELLOW . Utils::getVersion());

        /** @var string $world */
        $world = $this->getConfig()->get("HUB");
        
        if (!file_exists($this->getServer()->getDataPath() . "worlds/" . $world)) {
            API::createHub();
            $this->getLogger()->info("§eWorld $world Has Been Successfully Created");
            return;
        }

        if ($this->getServer()->getWorldManager()->loadWorld($world)) {
            $this->getLogger()->info("§eWorld $world Has Been Successfully Loaded");
       }
    }

    public function onEnable(): void {

     $this->getServer()->getNetwork()->setName($this->getConfig()->get("SERVER-MOTD"));

     $world = Server::getInstance()->getWorldManager()->getWorldByName(API::getHub());
     $world->setTime(1000);
     $world->stopTime();

     $this->initDatabase();

     Loader::initialize();
     IslandManager::loadIslands($this);
    }

    public function onDisable(): void {
      foreach ($this->getServer()->getOnlinePlayers() as $players) {
        if(!$players instanceof Player) return;

        $players->sendTitle("§cServer Restarting");
       }
    }
}