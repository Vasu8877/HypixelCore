<?php

declare(strict_types = 1);

namespace Biswajit\Core;

use pocketmine\Server;
use pocketmine\utils\Config;

class API {

  public static function createHub(): void {
    $worldPath = Skyblock::getInstance()->getDataFolder() . "HUB.zip"; 
    if(file_exists($worldPath))
    {
      if(!is_dir("worlds/" . self::getHub()))
      {
        $zip = new \ZipArchive();
        $zip->open($worldPath);
        mkdir(Server::getInstance()->getDataPath() . "worlds/" . self::getHub());
        $zip->extractTo(Server::getInstance()->getDataPath() . "worlds/" . self::getHub());
        $zip->close();
        Server::getInstance()->getWorldManager()->loadWorld(self::getHub());     
      }
    }
  }

  public static function getHub(): string {
    return Skyblock::getInstance()->getConfig()->get("HUB");
  }

  public static function getMessage(string $key): string {
    $File = new Config(Skyblock::getInstance()->getDataFolder() . "messages.yml", Config::YAML, []);
    return $File->get($key);
  }
}