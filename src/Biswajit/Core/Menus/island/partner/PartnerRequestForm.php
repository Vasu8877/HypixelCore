<?php

declare(strict_types = 1);

namespace Biswajit\Core\Menus\island\partner;

use pocketmine\player\Player;
use dktapps\pmforms\ModalForm;
use Biswajit\Core\Skyblock;
use Biswajit\Core\Managers\IslandManager;
use Biswajit\Core\Utils\Utils;

class PartnerRequestForm extends ModalForm
{

    public function __construct(Player $requestPlayer)
    {
        parent::__construct(
            Skyblock::$prefix . "Partnership Request",
            $requestPlayer->getName() . "The player wants to add you to his/her island as a partner!",
            function (Player $player, bool $choice) use ($requestPlayer): void {
                switch ($choice) {
                    case true:
                        IslandManager::partnerRequestConfirm($player, $requestPlayer->getName());
                        break;
                    case false:
                        $player->sendMessage(Utils::BT_MARK . "bYou did not accept the partner offer!");
                        if ($requestPlayer->isOnline()) {
                            $requestPlayer->sendMessage(Utils::BT_MARK . "bPartnership did not accept your offer!");
                        }
                        break;
                }
            },
            "Admit it",
            "reject"
        );
    }
}
