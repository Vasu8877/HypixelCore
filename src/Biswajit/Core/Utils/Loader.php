<?php

declare(strict_types = 1);

namespace Biswajit\Core\Utils;

use Biswajit\Core\Listeners\Player\PlayerJoin;
use Biswajit\Core\Skyblock;

class Loader {

  public static function initialize(): void
    {
        self::loadListeners();
    }

  public static function loadListeners(): void
    {
        $listeners = [
             new PlayerJoin()
        ];

        foreach ($listeners as $event){
           Skyblock::getInstance()->getServer()->getPluginManager()->registerEvents($event, Skyblock::getInstance());
        }
    }

}