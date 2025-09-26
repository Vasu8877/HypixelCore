<?php

declare(strict_types = 1);

namespace Biswajit\Core\Utils;

use Biswajit\Core\Skyblock;

class Utils {

    public static function getVersion(): string {
      return Skyblock::getInstance()->getDescription()->getVersion();
    }

    public static function getServerName(): string {
      return Skyblock::getInstance()->getConfig()->get("SERVER-NAME");
    }
}