<?php

declare(strict_types = 1);

namespace Biswajit\Core\Menus\bank;

use Biswajit\Core\Managers\BankManager;
use Biswajit\Core\Managers\EconomyManager;
use pocketmine\player\Player;
use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Input;
use dktapps\pmforms\element\Label;

class PayLoanMenu extends CustomForm
{
    public function __construct(Player $player) {
        $loan = BankManager::getLoan($player);
        parent::__construct(
            "§bPay §3Loan",
            [
                new Input("amount", "Please Enter A Numric Value", "", "$loan"),
                new Label("label", "§eTotal Loan§7: §b$loan")
            ],
            function (Player $player, CustomFormResponse $response) use ($loan): void {
                $result = $response->getString("amount");
         
                if(!is_numeric($result)) {
                    $player->sendMessage("§cError Please Enter A Number");
                    return;
                }

                if($result < 1) {
                    $player->sendMessage("§cError Can't Pay §e$result");
                    return;
                }

                if($loan < 1) {
                     $player->sendMessage("§cError You Haven't Aquired Loan");
                     return;
                }

                if($result > $loan) {
                    $player->sendMessage("§cError Unable To Pay §e$result");
                    return;
                }

                if(EconomyManager::getMoney($player) < $result) {
                    $player->sendMessage("§cError You Don't Have §e$result §cIn Your Purse");
                    return;
                }
           
                BankManager::reduceLoan($player, (float)$result);
                EconomyManager::subtractMoney($player, (float)$result);
                $player->sendMessage("§aSuccessfully Payed A Total Of §e$result");

                if(BankManager::getLoan($player) < 1) {
                  BankManager::setLoanTime($player, 0);
                }
            }
        );
    }
}
