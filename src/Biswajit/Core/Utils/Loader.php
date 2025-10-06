<?php

declare(strict_types = 1);

namespace Biswajit\Core\Utils;

use Biswajit\Core\Commands\player\IslandCommand;
use Biswajit\Core\Commands\player\JoinCommand;
use Biswajit\Core\Commands\player\VisitCommand;
use Biswajit\Core\Commands\player\WeatherCommand;
use Biswajit\Core\Listeners\Inventory\InventoryTransaction;
use Biswajit\Core\Listeners\Island\IslandListener;
use Biswajit\Core\Listeners\Player\PlayerCreation;
use Biswajit\Core\Listeners\Player\PlayerJoin;
use Biswajit\Core\Skyblock;

class Loader {

  public static function initialize(): void
    {
        self::loadListeners();
        self::loadCommands();
        ItemLoader::initialize();
    }

  public static function loadListeners(): void
    {
        $listeners = [
             new PlayerJoin(),
             new PlayerCreation(),
             new IslandListener(),
             new InventoryTransaction()
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
            new VisitCommand()
        ];

        foreach($commands as $cmd){
            Skyblock::getInstance()->getServer()->getCommandMap()->register("skyblock", $cmd);
        }

        $count = count($commands);
        Skyblock::getInstance()->getLogger()->info("§c{$count}§f command register !");
    }

}