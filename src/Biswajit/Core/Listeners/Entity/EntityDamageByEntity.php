<?php

declare(strict_types=1);

namespace Biswajit\Core\Listeners\Entity;

use Biswajit\Core\Player;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;

class EntityDamageByEntity implements Listener {

    public function onEntityDamage(EntityDamageEvent $event): void {
    if ($event instanceof EntityDamageByEntityEvent) {
        $damager = $event->getDamager();
        $entity = $event->getEntity();

        if ($damager instanceof Player && $entity instanceof Player) {
            $event->cancel();
         }

	if ($event->getCause() === EntityDamageEvent::CAUSE_STARVATION) {
        $event->cancel();
      }
     }
   }
 }