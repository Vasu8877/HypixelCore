<?php

declare(strict_types = 1);

namespace Biswajit\Core\Utils;

use Biswajit\Core\Commands\player\HubCommand;
use Biswajit\Core\Commands\player\IslandCommand;
use Biswajit\Core\Commands\player\JoinCommand;
use Biswajit\Core\Commands\player\VisitCommand;
use Biswajit\Core\Commands\player\WeatherCommand;
use Biswajit\Core\Commands\Staff\MultiWorld;
use Biswajit\Core\Listeners\Entity\EntityDamageByEntity;
use Biswajit\Core\Listeners\Entity\EntityRegainHealth;
use Biswajit\Core\Listeners\Entity\EntityTrampleFarmland;
use Biswajit\Core\Listeners\Inventory\InventoryTransaction;
use Biswajit\Core\Listeners\Server\IslandListener;
use Biswajit\Core\Listeners\Player\PlayerCreation;
use Biswajit\Core\Listeners\Player\PlayerExhaust;
use Biswajit\Core\Listeners\Player\PlayerJoin;
use Biswajit\Core\Listeners\Player\PlayerQuit;
use Biswajit\Core\Listeners\Server\HubListener;
use Biswajit\Core\Listeners\Server\QueryRegenerate;
use Biswajit\Core\Skyblock;

class Loader {

  public static function initialize(): void
    {
        self::loadListeners();
        self::loadCommands();
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
             new QueryRegenerate()
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
            new MultiWorld()
        ];

        foreach($commands as $cmd){
            Skyblock::getInstance()->getServer()->getCommandMap()->register("skyblock", $cmd);
        }

        $count = count($commands);
        Skyblock::getInstance()->getLogger()->info("§c{$count}§f command register !");
    }

}