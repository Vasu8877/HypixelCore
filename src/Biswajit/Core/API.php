<?php

declare(strict_types = 1);

namespace Biswajit\Core;

use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\Server;
use pocketmine\utils\Config;
use ZipArchive;

class API {
  /**
   * Loads or creates the hub world if it doesn't exist
   */
  public static function loadHub(): void {
    $world = Skyblock::getInstance()->getConfig()->get("HUB");
    $worldPath = Skyblock::getInstance()->getServer()->getDataPath() . "worlds/" . $world;

    if (!file_exists($worldPath)) {
      self::createHub();
      Skyblock::getInstance()->getLogger()->info("§aHub world '$world' has been created successfully");
    }
  }

  /**
   * Loads the hub world if it exists
   */
  public static function loadHubWorld(): void {
    $world = Skyblock::getInstance()->getConfig()->get("HUB");
    $worldPath = Skyblock::getInstance()->getServer()->getDataPath() . "worlds/" . $world;

    if (file_exists($worldPath)) {
      if (Skyblock::getInstance()->getServer()->getWorldManager()->loadWorld($world)) {
        Skyblock::getInstance()->getLogger()->info("§eHub world loaded successfully");
      } else {
        Skyblock::getInstance()->getLogger()->error("§cFailed to load hub world '$world'");
      }
    }
  }

  /**
   * Creates the hub world from zip file
   */
  public static function createHub(): void {
    $worldPath = Skyblock::getInstance()->getDataFolder() . "HUB.zip";
    $hubName = self::getHub();
    $targetPath = Server::getInstance()->getDataPath() . "worlds/" . $hubName;

    if (!file_exists($worldPath)) {
      Skyblock::getInstance()->getLogger()->error("§cHub world template not found!");
      return;
    }

    if (!is_dir($targetPath)) {
      $zip = new ZipArchive();
      if ($zip->open($worldPath) === true) {
        mkdir($targetPath);
        $zip->extractTo($targetPath);
        $zip->close();
        Server::getInstance()->getWorldManager()->loadWorld($hubName);
      } else {
        Skyblock::getInstance()->getLogger()->error("§cFailed to extract hub world!");
      }
    }
  }

  /**
   * Loads minion skin resources
   */
  public static function loadMinionSkins(): void {
    $path = Skyblock::getInstance()->getDataFolder() . "minion/minion.zip";
    if (!file_exists($path)) {
      Skyblock::getInstance()->getLogger()->error("§cMinion skins file not found!");
      return;
    }

    $zip = new ZipArchive();
    if ($zip->open($path) === true) {
      $zip->extractTo(Skyblock::getInstance()->getDataFolder() . "minion");
      $zip->close();
    }
  }

  /**
   * Gets the hub world name from config
   */
  public static function getHub(): string {
    return Skyblock::getInstance()->getConfig()->get("HUB");
  }

  /**
   * Sets and freezes hub world time
   */
  public static function setHubTime(): void {
    $world = Server::getInstance()->getWorldManager()->getWorldByName(self::getHub());
    if ($world !== null) {
      $world->setTime(1000);
      $world->stopTime();
    }
  }

  /**
   * Gets a message from messages.yml
   */
  public static function getMessage(string $key): string {
    $file = new Config(Skyblock::getInstance()->getDataFolder() . "messages.yml", Config::YAML, []);
    return $file->getNested($key) ?? "Message '$key' not found";
  }

  /**
   * Gets a custom Skyblock item by identifier
   */
  public static function getItem(string $identifier): Item {
    return StringToItemParser::getInstance()->parse("skyblock:$identifier");
  }
}