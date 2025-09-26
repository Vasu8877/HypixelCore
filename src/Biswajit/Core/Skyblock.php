<?php

declare(strict_types = 1);

namespace Biswajit\Core;

use Biswajit\Core\Utils\Loader;
use Biswajit\Core\Utils\Utils;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;

class Skyblock extends PluginBase {

    use SingletonTrait;

    public function onLoad(): void {
        self::$instance = $this;
        $this->reloadConfig();
        $this->saveResource("HUB.zip");
        $this->getLogger()->info("§l§bLoading SkyblockCore Version: ". TextFormat::YELLOW . Utils::getVersion());

        /** @var string $world */
        $world = $this->getConfig()->get("HUB");
        
        if (!file_exists($this->getServer()->getDataPath() . "worlds/" . $world)) {
            API::CreateHub();
            $this->getLogger()->info("§eWorld $world Has Been Successfully Created");
            return;
        }

        if ($this->getServer()->getWorldManager()->loadWorld($world)) {
            $this->getLogger()->info("§eWorld $world Has Been Successfully Loaded");
       }
    }

    public function onEnable(): void {

     $this->getServer()->getNetwork()->setName($this->getConfig()->get("SERVER-MOTD"));

     $this->saveResource("messages.yml");

     Loader::initialize();
    }
}