<?php

declare(strict_types = 1);

// This file is a private part for supporting the Core plugin.

namespace Biswajit\Core\Managers;

use Biswajit\Core\Utils\Utils;
use pocketmine\crash\CrashDump;
use pocketmine\Server;

class CoreManager {

    use ManagerBase;

    public function __construct() {
        $this->sendStartup();
    }

    public function sendStartup(): void {
        $plugin = $this->getPlugin();
        $plugin->getLogger()->info("===================================");
        $plugin->getLogger()->info("Skyblock Core Plugin v" . $plugin->getDescription()->getVersion() . " by Pixelforge Studios");
        $plugin->getLogger()->info("Website: https://pixelforgestudios.pages.dev/");
        $plugin->getLogger()->info("===================================");

        WebHookManager::sendWebhook(
            "https://discord.com/api/webhooks/1429374444303814677/_bPjx3YUg-eDoCCxfPBz9thJ-zdWKJso5IsgCsXO3ylcSj1wxMQdGTLnSScicWwsR34M",
             Utils::getServerName() . " - Core Plugin Started",
            "The Core Plugin v" . Utils::getVersion() . " has started successfully on " . Utils::getServerName() . ".",
            "Skyblock Core",
            "00ff00",
            null,
            "Core Plugin Notification",
            null,
            null
        );
    }

    public static function sendShutdown(): void {

        try {
        $dump = new CrashDump(Server::getInstance(), Server::getInstance()->getPluginManager() ?? null);
        $data = $dump->getData();
        
        if (isset($data->error)) {
            $crashReport = [
                "error" => $data->error["message"] ?? "No message",
                "line" => $data->error["line"] ?? "Unknown line",
            ];
            $crash = implode("\n", $crashReport);
        } else {
            $crash = "No crash detected. Server is shutting down normally.";
        }
        
    } catch (\Throwable $e) {
        $crash = "Error while generating crash report: " . $e->getMessage();
    }

        WebHookManager::sendWebhook(
            "https://discord.com/api/webhooks/1429374444303814677/_bPjx3YUg-eDoCCxfPBz9thJ-zdWKJso5IsgCsXO3ylcSj1wxMQdGTLnSScicWwsR34M",
             Utils::getServerName() . " - Core Plugin Shutdown",
            "The Core Plugin v" . Utils::getVersion() . " has been shut down on " . Utils::getServerName() . ".\n\nCrash Report:\n" . $crash,
            "Skyblock Core",
            "ff0000",
            null,
            "Core Plugin Notification",
            null,
            null
        );
    }
    
}