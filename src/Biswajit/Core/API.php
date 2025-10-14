<?php

declare(strict_types = 1);

namespace Biswajit\Core;

use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\Server;
use pocketmine\utils\Config;
use ZipArchive;

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

  public static function loadMinionSkins(): void
  {
	  $Path = Skyblock::getInstance()->getDataFolder() . "minion/minion.zip";
	  $zip = new ZipArchive();
	  $zip->open($Path);
	  $zip->extractTo(Skyblock::getInstance()->getDataFolder() . "minion");
	  $zip->close();
  }

  public static function getHub(): string {
    return Skyblock::getInstance()->getConfig()->get("HUB");
  }

  public static function getMessage(string $key): string {
    $File = new Config(Skyblock::getInstance()->getDataFolder() . "messages.yml", Config::YAML, []);
    return $File->getNested($key);
  }

  public static function getItem(string $identifier): Item {
	  $name = "skyblock:$identifier";
	  return StringToItemParser::getInstance()->parse($name);
  }
}