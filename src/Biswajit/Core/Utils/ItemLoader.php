<?php

declare(strict_types = 1);

namespace Biswajit\Core\Utils;

use Biswajit\Core\Items\items\menuItem;
use customiesdevs\customies\item\CustomiesItemFactory;

class ItemLoader {

  public static function initialize(): void
    {
       $factory = CustomiesItemFactory::getInstance();
       $factory->registerItem(fn() => clone new menuItem(), "skyblock:menu", null);
    }
}