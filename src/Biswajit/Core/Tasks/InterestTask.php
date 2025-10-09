<?php

namespace Biswajit\Core\Tasks;

use Biswajit\Core\Managers\BankManager;
use Biswajit\Core\Player;
use Biswajit\Core\Skyblock;
use pocketmine\Server;
use pocketmine\scheduler\Task;

class InterestTask extends Task
{
    
    /** @var Skyblock */
    private Skyblock $source;
    
    /** @var Player */
    private Player $player;
    
    /** @var Bool */
    private $firstTime;
    
    public function __construct(Skyblock $source, Player $player)
    {
        $this->source = $source;
        $this->player = $player;
        $this->firstTime = true;
    }
    
    public function onRun(): void
    {
      if(!$this->firstTime)
      {
        $playerBankMoney = BankManager::getBankMoney($this->player);
        $interest = $this->source->getInstance()->getConfig()->getNested("Interest");
        $addingMoney = $playerBankMoney/$interest;
        BankManager::addBankMoney($this->player, $addingMoney);
        BankManager::addLoanMerit($this->player, 5);
        $player = Server::getInstance()->getPlayerExact($this->player);
        if($player instanceof Player)
        {
          $player->sendMessage(" §aRecieved §e$interest% §ainsterest in your bank account");
        }
      }else{
        $this->firstTime = false;
      }
    }
}