<?php

declare(strict_types=1);

namespace Biswajit\Core\Commands\Staff;

use Biswajit\Core\API;
use Biswajit\Core\Managers\EconomyManager;
use Biswajit\Core\Player;
use Biswajit\Core\Skyblock;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class GemsCommand extends Command
{
    public function __construct()
    {
        parent::__construct("gems", "Manage player gems", "/gems <subcommand> [args]");
        $this->setPermission("staff.economy.cmd");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$this->testPermission($sender)) {
            $sender->sendMessage(Skyblock::$prefix . API::getMessage("gems.no-permission"));
            return false;
        }

        if (empty($args)) {
            $this->sendHelp($sender);
            return true;
        }

        $subcommand = strtolower(array_shift($args));

        switch ($subcommand) {
            case "balance":
            case "bal":
                return $this->handleBalance($sender, $args);
            case "give":
                return $this->handleGive($sender, $args);
            case "take":
                return $this->handleTake($sender, $args);
            case "set":
                return $this->handleSet($sender, $args);
            case "help":
            default:
                $this->sendHelp($sender);
                return true;
        }
    }

    private function handleBalance(CommandSender $sender, array $args): bool
    {
        if (count($args) === 0) {
            if (!$sender instanceof Player) {
                $sender->sendMessage(Skyblock::$prefix . API::getMessage("gems.console-error"));
                return false;
            }
            $amount = EconomyManager::getGems($sender);
            $sender->sendMessage(str_replace("{AMOUNT}", (string)$amount, API::getMessage("gems.balance-self")));
            return true;
        }

        $playerName = $args[0];
        $player = $sender->getServer()->getPlayerExact($playerName);
        if ($player === null) {
            $sender->sendMessage(str_replace("{PLAYER}", $playerName, API::getMessage("gems.balance-player-not-found")));
            return false;
        }

        $amount = EconomyManager::getGems($player);
        $sender->sendMessage(str_replace(["{PLAYER}", "{AMOUNT}"], [$playerName, (string)$amount], API::getMessage("gems.balance-other")));
        return true;
    }

    private function handleGive(CommandSender $sender, array $args): bool
    {
        if (count($args) < 2) {
            $sender->sendMessage(Skyblock::$prefix . API::getMessage("gems.give-usage"));
            return false;
        }

        $playerName = $args[0];
        $amount = $args[1];

        if (!is_numeric($amount) || $amount <= 0) {
            $sender->sendMessage(Skyblock::$prefix . API::getMessage("gems.give-invalid-amount"));
            return false;
        }

        $player = $sender->getServer()->getPlayerExact($playerName);
        if ($player === null) {
            $sender->sendMessage(str_replace("{PLAYER}", $playerName, API::getMessage("gems.give-player-not-found")));
            return false;
        }

        EconomyManager::addGems($player, (float)$amount);
        $sender->sendMessage(str_replace(["{PLAYER}", "{AMOUNT}"], [$playerName, $amount], API::getMessage("gems.give-success")));
        return true;
    }

    private function handleTake(CommandSender $sender, array $args): bool
    {
        if (count($args) < 2) {
            $sender->sendMessage(Skyblock::$prefix . API::getMessage("gems.take-usage"));
            return false;
        }

        $playerName = $args[0];
        $amount = $args[1];

        if (!is_numeric($amount) || $amount <= 0) {
            $sender->sendMessage(Skyblock::$prefix . API::getMessage("gems.take-invalid-amount"));
            return false;
        }

        $player = $sender->getServer()->getPlayerExact($playerName);
        if ($player === null) {
            $sender->sendMessage(str_replace("{PLAYER}", $playerName, API::getMessage("gems.take-player-not-found")));
            return false;
        }

        $currentBalance = EconomyManager::getGems($player);
        if ($currentBalance < $amount) {
            $sender->sendMessage(str_replace(["{PLAYER}", "{AMOUNT}"], [$playerName, $currentBalance], API::getMessage("gems.take-insufficient-funds")));
            return false;
        }

        EconomyManager::subtractGems($player, (float)$amount);
        $sender->sendMessage(str_replace(["{PLAYER}", "{AMOUNT}"], [$playerName, $amount], API::getMessage("gems.take-success")));
        return true;
    }

    private function handleSet(CommandSender $sender, array $args): bool
    {
        if (count($args) < 2) {
            $sender->sendMessage(Skyblock::$prefix . API::getMessage("gems.set-usage"));
            return false;
        }

        $playerName = $args[0];
        $amount = $args[1];

        if (!is_numeric($amount) || $amount < 0) {
            $sender->sendMessage(Skyblock::$prefix . API::getMessage("gems.set-invalid-amount"));
            return false;
        }

        $player = $sender->getServer()->getPlayerExact($playerName);
        if ($player === null) {
            $sender->sendMessage(str_replace("{PLAYER}", $playerName, API::getMessage("gems.set-player-not-found")));
            return false;
        }

        EconomyManager::setGems($player, (float)$amount);
        $sender->sendMessage(str_replace(["{PLAYER}", "{AMOUNT}"], [$playerName, $amount], API::getMessage("gems.set-success")));
        return true;
    }

    private function sendHelp(CommandSender $sender): void
    {
        $sender->sendMessage(Skyblock::$prefix . API::getMessage("gems.help-title"));
        $sender->sendMessage(Skyblock::$prefix . API::getMessage("gems.help-balance"));
        $sender->sendMessage(Skyblock::$prefix . API::getMessage("gems.help-give"));
        $sender->sendMessage(Skyblock::$prefix . API::getMessage("gems.help-take"));
        $sender->sendMessage(Skyblock::$prefix . API::getMessage("gems.help-set"));
        $sender->sendMessage(Skyblock::$prefix . API::getMessage("gems.help-help"));
    }
}
