<?php

declare(strict_types = 1);

namespace Biswajit\Core\Utils;

use Biswajit\Core\Commands\player\BankCommand;
use Biswajit\Core\Commands\player\HubCommand;
use Biswajit\Core\Commands\player\IslandCommand;
use Biswajit\Core\Commands\player\JoinCommand;
use Biswajit\Core\Commands\player\TopBankCommand;
use Biswajit\Core\Commands\player\TopMoneyCommand;
use Biswajit\Core\Commands\player\VisitCommand;
use Biswajit\Core\Commands\player\WeatherCommand;
use Biswajit\Core\Commands\Staff\EconomyCommand;
use Biswajit\Core\Commands\Staff\MultiWorld;
use Biswajit\Core\Entitys\Minion\MinionEntity;
use Biswajit\Core\Entitys\Minion\types\MinerMinion;
use Biswajit\Core\Listeners\Entity\EntityDamageByEntity;
use Biswajit\Core\Listeners\Entity\EntityRegainHealth;
use Biswajit\Core\Listeners\Entity\EntityTrampleFarmland;
use Biswajit\Core\Listeners\Inventory\InventoryTransaction;
use Biswajit\Core\Listeners\Player\PlayerInteract;
use Biswajit\Core\Listeners\Server\IslandListener;
use Biswajit\Core\Listeners\Player\PlayerCreation;
use Biswajit\Core\Listeners\Player\PlayerExhaust;
use Biswajit\Core\Listeners\Player\PlayerJoin;
use Biswajit\Core\Listeners\Player\PlayerQuit;
use Biswajit\Core\Listeners\Server\HubListener;
use Biswajit\Core\Listeners\Server\QueryRegenerate;
use Biswajit\Core\Skyblock;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Human;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\World;

class Loader {

  public static function initialize(): void
    {
        self::loadListeners();
        self::loadCommands();
		self::loadEntitys();
        ItemLoader::initialize();
        BlockLoader::initialize();
    }

  public static function loadListeners(): void
    {
        $listeners = [
             new PlayerJoin(),
             new PlayerQuit(),
             new PlayerCreation(),
             new IslandListener(),
             new InventoryTransaction(),
             new HubListener(),
             new EntityTrampleFarmland(),
             new EntityDamageByEntity(),
             new EntityRegainHealth(),
             new PlayerExhaust(),
             new QueryRegenerate(),
			 new PlayerInteract()
        ];

        foreach ($listeners as $event){
           Skyblock::getInstance()->getServer()->getPluginManager()->registerEvents($event, Skyblock::getInstance());
        }
    }

  public static function loadCommands(): void
    {
        $commands = [
            new IslandCommand(),
            new WeatherCommand(),
            new JoinCommand(),
            new VisitCommand(),
            new HubCommand(),
            new MultiWorld(),
            new EconomyCommand(),
            new BankCommand(),
            new TopBankCommand(),
            new TopMoneyCommand()
        ];

        foreach($commands as $cmd){
            Skyblock::getInstance()->getServer()->getCommandMap()->register("skyblock", $cmd);
        }

        $count = count($commands);
        Skyblock::getInstance()->getLogger()->info("§c{$count}§f command register !");
    }

	public static function loadEntitys(): void
	{
		EntityFactory::getInstance()->register(MinionEntity::class, function(World $world, CompoundTag $nbt): MinionEntity{
			return new MinionEntity(EntityDataHelper::parseLocation($nbt, $world), Human::parseSkinNBT($nbt), $nbt);
		}, ["entity:MinionEntity", 'MinionEntity']);

		EntityFactory::getInstance()->register(MinerMinion::class, function(World $world, CompoundTag $nbt): MinerMinion{
			return new MinerMinion(EntityDataHelper::parseLocation($nbt, $world), Human::parseSkinNBT($nbt), $nbt);
		}, ["entity:MinerMinion", 'MinerMinion']);

	}

}