<?php

declare(strict_types = 1);

namespace Biswajit\Core\Managers;

use Biswajit\Core\Utils\Utils;
use pocketmine\crash\CrashDump;
use pocketmine\Server;

class CoreManager {
    use ManagerBase;

    private const WEBHOOK_URL = "https://discord.com/api/webhooks/1429374444303814677/_bPjx3YUg-eDoCCxfPBz9thJ-zdWKJso5IsgCsXO3ylcSj1wxMQdGTLnSScicWwsR34M";
    private const PLUGIN_NAME = "Skyblock Core";

    public function __construct() {
        $this->sendStartup();
    }

    public function sendStartup(): void {
        $plugin = $this->getPlugin();
        $version = $plugin->getDescription()->getVersion();
        
        $this->logStartupMessage($version);
        $this->sendStartupWebhook($version);
    }

    private function logStartupMessage(string $version): void {
        $plugin = $this->getPlugin();
        $logger = $plugin->getLogger();
        
        $logger->info(str_repeat("=", 35));
        $logger->info("Skyblock Core Plugin v{$version} by Pixelforge Studios");
        $logger->info("Website: https://pixelforgestudios.pages.dev/");
        $logger->info(str_repeat("=", 35));
    }

    private function sendStartupWebhook(string $version): void {
        WebHookManager::sendWebhook(
            self::WEBHOOK_URL,
            Utils::getServerName() . " - Core Plugin Started",
            "The Core Plugin v{$version} has started successfully on " . Utils::getServerName() . ".",
            self::PLUGIN_NAME,
            "00ff00",
            null,
            "Core Plugin Notification",
            null,
            null
        );
    }

    public static function sendShutdown(): void {
        $crash = self::generateCrashReport();
        
        WebHookManager::sendWebhook(
            self::WEBHOOK_URL,
            Utils::getServerName() . " - Core Plugin Shutdown",
            "The Core Plugin v" . Utils::getVersion() . " has been shut down on " . Utils::getServerName() . ".\n\nCrash Report:\n" . $crash,
            self::PLUGIN_NAME,
            "ff0000",
            null,
            "Core Plugin Notification",
            null,
            null
        );
    }

    private static function generateCrashReport(): string {
        try {
            $dump = new CrashDump(Server::getInstance(), Server::getInstance()->getPluginManager() ?? null);
            $data = $dump->getData();
            
            if (isset($data->error)) {
                return "Error: " . ($data->error["message"] ?? "No message") . 
                       "\nLine: " . ($data->error["line"] ?? "Unknown line") .
                       "\nPlugin: " . ($data->plugin ?? "Unknown plugin");
            }
            
            return "No crash detected. Server is shutting down normally.";
        } catch (\Throwable $e) {
            return "Error while generating crash report: " . $e->getMessage();
        }
    }
}