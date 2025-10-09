<?php

declare(strict_types=1);

namespace Biswajit\Core\Commands\Staff;

use Biswajit\Core\API;
use Biswajit\Core\Managers\EconomyManager;
use Biswajit\Core\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class EconomyCommand extends Command
{
    public function __construct()
    {
        parent::__construct("eco", "Manage player economy", "/eco <subcommand> [args]");
        $this->setPermission("staff.economy.cmd");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$this->testPermission($sender)) {
            $sender->sendMessage(API::getMessage("economy.no-permission"));
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
                $sender->sendMessage(API::getMessage("economy.console-error"));
                return false;
            }
            $amount = EconomyManager::getMoney($sender);
            $sender->sendMessage(str_replace("{AMOUNT}", (string)$amount, API::getMessage("economy.balance-self")));
            return true;
        }

        $playerName = $args[0];
        $player = $sender->getServer()->getPlayerExact($playerName);
        if ($player === null) {
            $sender->sendMessage(str_replace("{PLAYER}", $playerName, API::getMessage("economy.balance-player-not-found")));
            return false;
        }

        $amount = EconomyManager::getMoney($player);
        $sender->sendMessage(str_replace(["{PLAYER}", "{AMOUNT}"], [$playerName, (string)$amount], API::getMessage("economy.balance-other")));
        return true;
    }

    private function handleGive(CommandSender $sender, array $args): bool
    {
        if (count($args) < 2) {
            $sender->sendMessage(API::getMessage("economy.give-usage"));
            return false;
        }

        $playerName = $args[0];
        $amount = $args[1];

        if (!is_numeric($amount) || $amount <= 0) {
            $sender->sendMessage(API::getMessage("economy.give-invalid-amount"));
            return false;
        }

        $player = $sender->getServer()->getPlayerExact($playerName);
        if ($player === null) {
            $sender->sendMessage(str_replace("{PLAYER}", $playerName, API::getMessage("economy.give-player-not-found")));
            return false;
        }

        EconomyManager::addMoney($player, (float)$amount);
        $sender->sendMessage(str_replace(["{PLAYER}", "{AMOUNT}"], [$playerName, $amount], API::getMessage("economy.give-success")));
        return true;
    }

    private function handleTake(CommandSender $sender, array $args): bool
    {
        if (count($args) < 2) {
            $sender->sendMessage(API::getMessage("economy.take-usage"));
            return false;
        }

        $playerName = $args[0];
        $amount = $args[1];

        if (!is_numeric($amount) || $amount <= 0) {
            $sender->sendMessage(API::getMessage("economy.take-invalid-amount"));
            return false;
        }

        $player = $sender->getServer()->getPlayerExact($playerName);
        if ($player === null) {
            $sender->sendMessage(str_replace("{PLAYER}", $playerName, API::getMessage("economy.take-player-not-found")));
            return false;
        }

        $currentBalance = EconomyManager::getMoney($player);
        if ($currentBalance < $amount) {
            $sender->sendMessage(str_replace(["{PLAYER}", "{AMOUNT}"], [$playerName, $currentBalance], API::getMessage("economy.take-insufficient-funds")));
            return false;
        }

        EconomyManager::subtractMoney($player, (float)$amount);
        $sender->sendMessage(str_replace(["{PLAYER}", "{AMOUNT}"], [$playerName, $amount], API::getMessage("economy.take-success")));
        return true;
    }

    private function handleSet(CommandSender $sender, array $args): bool
    {
        if (count($args) < 2) {
            $sender->sendMessage(API::getMessage("economy.set-usage"));
            return false;
        }

        $playerName = $args[0];
        $amount = $args[1];

        if (!is_numeric($amount) || $amount < 0) {
            $sender->sendMessage(API::getMessage("economy.set-invalid-amount"));
            return false;
        }

        $player = $sender->getServer()->getPlayerExact($playerName);
        if ($player === null) {
            $sender->sendMessage(str_replace("{PLAYER}", $playerName, API::getMessage("economy.set-player-not-found")));
            return false;
        }

        EconomyManager::setMoney($player, (float)$amount);
        $sender->sendMessage(str_replace(["{PLAYER}", "{AMOUNT}"], [$playerName, $amount], API::getMessage("economy.set-success")));
        return true;
    }

    private function sendHelp(CommandSender $sender): void
    {
        $sender->sendMessage(API::getMessage("economy.help-title"));
        $sender->sendMessage(API::getMessage("economy.help-balance"));
        $sender->sendMessage(API::getMessage("economy.help-give"));
        $sender->sendMessage(API::getMessage("economy.help-take"));
        $sender->sendMessage(API::getMessage("economy.help-set"));
        $sender->sendMessage(API::getMessage("economy.help-help"));
    }
}
