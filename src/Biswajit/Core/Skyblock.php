<?php

declare(strict_types = 1);

namespace Biswajit\Core;

use Biswajit\Core\Managers\BlockManager;
use Biswajit\Core\Managers\IslandManager;
use Biswajit\Core\Managers\Worlds\IslandGenerator;
use Biswajit\Core\Tasks\ActionbarTask;
use Biswajit\Core\Tasks\StatsRegainTask;
use Biswajit\Core\Utils\Loader;
use Biswajit\Core\Utils\Utils;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\plugin\PluginBase;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;
use pocketmine\world\generator\GeneratorManager;
use ReflectionProperty;
use Symfony\Component\Filesystem\Path;

class Skyblock extends PluginBase {

    use SingletonTrait;
    use Database;

    public static string $prefix;
    public const FAKE_ENCH_ID = 500;
    private array $handlers = [];

    public function onLoad(): void {
        self::$instance = $this;
        self::$prefix = Skyblock::getInstance()->getConfig()->get("PREFIX");

        $this->reloadConfig();

        GeneratorManager::getInstance()->addGenerator(IslandGenerator::class, "void", fn() => null, true);
        EnchantmentIdMap::getInstance()->register(self::FAKE_ENCH_ID, new Enchantment("Glow", 1, 0xffff, 0x0, 1));
        
        @mkdir($this->getDataFolder() . "island");
        $this->saveResource("HUB.zip");
        $this->saveResource("messages.yml");
        $this->saveResource("Skyblock.mcpack");

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

     BlockManager::initialise();
     IslandManager::initialise();
     $this->initDatabase();
     
     $world = Server::getInstance()->getWorldManager()->getWorldByName(API::getHub());
     $world->setTime(1000);
     $world->stopTime();

     $this->getScheduler()->scheduleRepeatingTask(new ActionbarTask(), 10);
     $this->getScheduler()->scheduleRepeatingTask(new StatsRegainTask(), 100);


     $rpManager = $this->getServer()->getResourcePackManager();
	   $rpManager->setResourceStack(array_merge($rpManager->getResourceStack(), [new ZippedResourcePack(Path::join($this->getDataFolder(), "Skyblock.mcpack"))]));
	   (new ReflectionProperty($rpManager, "serverForceResources"))->setValue($rpManager, true);

     Loader::initialize();
    }

    public function onDisable(): void {
      foreach ($this->getServer()->getOnlinePlayers() as $players) {
        foreach ($players as $player) {
        if(!$player instanceof Player) return;

        $player->sendTitle("§cServer Restarting");
        $player->save();
       }
      }
       
       BlockManager::Disable();
    }

    public function addHandler($hander): void {
		$this->handlers[] = $hander;
	}

}