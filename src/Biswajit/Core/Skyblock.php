<?php

declare(strict_types = 1);

namespace Biswajit\Core;

use Biswajit\Core\Managers\BlockManager;
use Biswajit\Core\Managers\Worlds\IslandGenerator;
use Biswajit\Core\Tasks\ActionbarTask;
use Biswajit\Core\Tasks\AsynTasks\loadWorldsTask;
use Biswajit\Core\Tasks\LoanTask;
use Biswajit\Core\Tasks\StatsRegainTask;
use Biswajit\Core\Utils\Loader;
use Biswajit\Core\Utils\Utils;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\plugin\PluginBase;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;
use pocketmine\world\generator\GeneratorManager;
use ReflectionException;
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

        $worlds = [
            "hub" => [
                "url" => "https://github.com/pixelforge-studios-PMMP/SkyblockCoreWorlds/releases/download/Worlds/HUB.zip",
                "path" => $this->getDataFolder() . "HUB.zip"
            ],
            "islands" => [
                "url" => "https://github.com/pixelforge-studios-PMMP/SkyblockCoreWorlds/releases/download/Worlds/Islands.zip", 
                "path" => $this->getDataFolder() . "island" . DIRECTORY_SEPARATOR . "Islands.zip"
            ]
        ];

        foreach ($worlds as $name => $data) {
            try {
                $this->getLogger()->info("Downloading " . $name . " world...");
                $this->getServer()->getAsyncPool()->submitTask(new loadWorldsTask($data["url"], $data["path"]));
            } catch (\Exception $e) {
                $this->getLogger()->error("Failed to download " . $name . " world: " . $e->getMessage());
            }
        }

        GeneratorManager::getInstance()->addGenerator(IslandGenerator::class, "void", fn() => null, true);
        EnchantmentIdMap::getInstance()->register(self::FAKE_ENCH_ID, new Enchantment("Glow", 1, 0xffff, 0x0, 1));
        
        @mkdir($this->getDataFolder() . "island");
		@mkdir($this->getDataFolder() . "minion");
		$this->saveResource("minion/minion.zip");
        $this->saveResource("minion/minion.geo.json");
        $this->saveResource("messages.yml");
        $this->saveResource("Skyblock.mcpack");

        $this->getLogger()->info("§l§bLoading SkyblockCore Version: ". TextFormat::YELLOW . Utils::getVersion());

    }

	/**
	 * @throws ReflectionException
	 */
	public function onEnable(): void {

     $this->getServer()->getNetwork()->setName($this->getConfig()->get("SERVER-MOTD"));

     BlockManager::initialise();

     $this->initDatabase();

	 API::loadMinionSkins();
     API::loadHubWorld();
     API::setHubTime();

     $this->getScheduler()->scheduleRepeatingTask(new ActionbarTask(), 10);
     $this->getScheduler()->scheduleRepeatingTask(new StatsRegainTask(), 100);
     $this->getScheduler()->scheduleRepeatingTask(new LoanTask($this), 100);

     $rpManager = $this->getServer()->getResourcePackManager();
	 $rpManager->setResourceStack(array_merge($rpManager->getResourceStack(), [new ZippedResourcePack(Path::join($this->getDataFolder(), "Skyblock.mcpack"))]));
	 (new ReflectionProperty($rpManager, "serverForceResources"))->setValue($rpManager, true);

     Loader::initialize();

	if(!InvMenuHandler::isRegistered()) {
		InvMenuHandler::register($this);
	}
 }

    public function onDisable(): void {
      foreach ($this->getServer()->getOnlinePlayers() as $player) {
        if (!$player instanceof Player) continue;

        $player->sendTitle("§cServer Restarting");
        $player->save();
    }

    BlockManager::Disable();
}

    public function addHandler($handler): void {
		$this->handlers[] = $handler;
	}
}