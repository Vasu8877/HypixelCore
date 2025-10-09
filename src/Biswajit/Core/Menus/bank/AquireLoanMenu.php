<?php

declare(strict_types = 1);

namespace Biswajit\Core\Menus\bank;

use Biswajit\Core\Managers\BankManager;
use Biswajit\Core\Managers\EconomyManager;
use Biswajit\Core\Utils\Utils;
use pocketmine\player\Player;
use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Dropdown;
use dktapps\pmforms\element\Label;
use dktapps\pmforms\element\Slider;

class AquireLoanMenu extends CustomForm
{
    public function __construct(Player $player) {
        $maxAquiring = BankManager::getLoanMerit($player) * 10000;
        parent::__construct(
            "§bAquire §3Loan",
            [
                new Slider("slider", "Please Select A Value", 0, $maxAquiring),
                new Dropdown("dropdown", "Select Time", array("1 Hour", "10 Hour", "1 day", "2 Day")),
                new Label("label", "§bCan Aquire§7: §3$maxAquiring\n§l§cWARNING§7: §r§anot paying back Loans is dangerous. if you acquire a loan and didn't pay it back under the time you chose, §cYour island will be RESETTED instantly. " . Utils::getServerName() . " is not Responsible for any kind of damage caused by Loans. thanks.")
            ],
            function (Player $player, CustomFormResponse $response) use($maxAquiring): void {
                $result1 = $response->getFloat("slider");
                $result2 = $response->getInt("dropdown");

                if($result1 > $maxAquiring) {
                    $player->sendMessage("§cYou can't aquire §e$result1");
                    return;
                }

                if($result1 < 1) {
                    $player->sendMessage("§cYou can't aquire §e$result1");
                    return;
                }
        
                if(BankManager::getLoanMerit($player) < 50) {
                    $player->sendMessage("§cYour merit is less than 50");
                    return;
                }

                if(BankManager::getLoan($player) > 0) {
                    $player->sendMessage("§cYou have already aquired a loan");
                    return;
                }

              BankManager::addLoan($player, $result1);
              EconomyManager::addMoney($player, $result1);
              $player->sendMessage("§aSuccsesfully Given Loan Off $result1");
              $array = array("1 Hour", "10 Hour", "1 day", "2 Day");
              if($array[$result2] === "1 Hour")
              {
                BankManager::setLoanTime($player, time() + 3600);
              }elseif($array[$result2] === "10 Hour")
              {
                BankManager::setLoanTime($player, time() + 36000);
              }elseif($array[$result2] === "1 Day")
              {
                BankManager::setLoanTime($player, time() + 86400);
              }elseif($array[$result2] === "2 Day")
              {
                BankManager::setLoanTime($player, time() + 172800);
              }
            }
        );
    }
}
