<?php

declare(strict_types=1);

namespace Biswajit\Core\Listeners\Server;

use Biswajit\Core\API;
use Biswajit\Core\Managers\BlockManager;
use Biswajit\Core\Managers\IslandManager;
use Biswajit\Core\Player;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\Server;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\math\AxisAlignedBB;
use pocketmine\world\World;

class HubListener implements Listener
{

public function onEntityDamage(EntityDamageEvent $event): void
{
    $entity = $event->getEntity();

    if (!$entity instanceof Player) {
        return;
    }

    $worldName = $entity->getWorld()->getFolderName();

    if(API::getHub() !== $worldName) return;

     if ($event->getCause() === EntityDamageEvent::CAUSE_VOID) {
         $event->cancel();
         $world = $entity->getWorld();
         $entity->teleport($world->getSpawnLocation());
         
     }

         $cancelCauses = [
         EntityDamageEvent::CAUSE_FALL,
         EntityDamageEvent::CAUSE_FIRE,
         EntityDamageEvent::CAUSE_FIRE_TICK,
         EntityDamageEvent::CAUSE_DROWNING,
         EntityDamageEvent::CAUSE_SUFFOCATION,
         EntityDamageEvent::CAUSE_MAGIC
        ];

    if (in_array($event->getCause(), $cancelCauses, true)) {
        $event->cancel();
      }
}

    public function onMove(PlayerMoveEvent $event): void
    {
        $player = $event->getPlayer();
        $worldName = $player->getPosition()->getWorld()->getFolderName();
        $block = $player->getWorld()->getBlock($player->getPosition());

        if (!$player instanceof Player) {
            return;
        }

      if(API::getHub() !== $worldName) return;
      
          if($block->getName() === "End Portal") {
             $worldPath = Server::getInstance()->getDataPath() . "worlds/" . $player->getName();
               if (file_exists($worldPath)) {
                 IslandManager::teleportToIsland($player);
                 return;
           }
           
           $player->sendMessage("§cYou Dont Have A Island To Teleport !");
           $player->teleport($player->getWorld()->getSafeSpawn());
      }
    }

public function onPlace(BlockPlaceEvent $event): void
{
    $player = $event->getPlayer();

     if (!$player instanceof Player) {
        return;
    }

    $worldName = $player->getPosition()->getWorld()->getFolderName();
  
    if(API::getHub() !== $worldName) return;

      if(!Server::getInstance()->isOp($player->getName())){
           $event->cancel();
       }
  }
  
   public function SignChange(SignChangeEvent $event): void
    {
      $player = $event->getPlayer();
      $worldName = $player->getWorld()->getFolderName();
      
      if(API::getHub() !== $worldName) return;

      $event->cancel();
    }

  public function onBlockBreak(BlockBreakEvent $event): void
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $blocks = $event->getBlock();
        $world = $block->getPosition()->getWorld();
        $worldName = $player->getWorld()->getFolderName();

        if(API::getHub() !== $worldName) return;

        $event->cancel();
        $Mine = new AxisAlignedBB(13.00, (float) World::Y_MIN, 112.00, 255.00, (float) World::Y_MAX, 461.00);
            if ($Mine->isVectorInXZ($player->getPosition()) && in_array($block->getTypeId(), BlockManager::$mineBlocks)) {
                    $event->uncancel();
                    BlockManager::mineBlockRespawn($block, $blocks->getPosition());
                    return;
                  }

        $forest = new AxisAlignedBB(-231.00, (float) World::Y_MIN, -554.00, 26.00, (float) World::Y_MAX, -120.00);
            if ($forest->isVectorInXZ($player->getPosition()) && in_array($block->getTypeId(), BlockManager::$forest)) {
                    $event->uncancel();
                    BlockManager::mineBlockRespawn($block, $block->getPosition());
                    return;
                }

            if(in_array($block->getTypeId(), BlockManager::$farming)) {
                    $event->uncancel();
                    BlockManager::mineBlockRespawn($block, $block->getPosition());
                }

            $drops = $event->getDrops();

		        foreach ($drops as $drop) {
			      !$player->getInventory()->canAddItem($drop) ? $world->dropItem($block->getPosition(), $drop) : $player->getInventory()->addItem($drop);
		      	!$player->getXpManager()->canPickupXp() ? $world->dropExperience($block->getPosition(), $event->getXpDropAmount()) : $player->getXpManager()->addXp($event->getXpDropAmount());
	      	}

           $event->setDrops([]);

           if(Server::getInstance()->isOp($player->getName())){
                $event->uncancel();
              }
        }
 }