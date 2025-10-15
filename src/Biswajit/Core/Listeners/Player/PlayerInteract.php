<?php

namespace Biswajit\Core\Listeners\Player;

use Biswajit\Core\API;
use Biswajit\Core\Entitys\Minion\types\FarmerMinion;
use Biswajit\Core\Entitys\Minion\types\MinerMinion;
use Biswajit\Core\Items\items\minionHeads;
use Biswajit\Core\Skyblock;
use Biswajit\Core\Utils\Utils;
use JsonException;
use pocketmine\block\BlockTypeIds;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\nbt\tag\CompoundTag;

class PlayerInteract implements Listener
{

	/**
	 * @throws JsonException
	 */
	public function onPlayerInteract(PlayerInteractEvent $event): void {
		$player = $event->getPlayer();
		$item = $event->getItem();
		$world = $player->getWorld();
		$block = $event->getBlock();
		$position = $block->getPosition();

		if($block->getTypeId() === BlockTypeIds::ITEM_FRAME && $world === API::getHub()) return;

		if (!($item instanceof minionHeads)) return;

		$location = new Location(
			$position->getX() + 0.5,
			$position->getY() + 1,
			$position->getZ() + 0.5,
			$position->getWorld(),
			0.0,
			0.0
		);

		$WorkerItem = $player->getInventory()->getItemInHand();
		$player->getInventory()->setItemInHand($WorkerItem->setCount($WorkerItem->getCount() - 1));

		if (!is_null($item->getNamedTag()->getTag("Information"))) {
			$namedTag = $item->getNamedTag()->getTag("Information");
			$nbt = CompoundTag::create()->setTag("Information", $namedTag);
		} else {
			$nbt = CompoundTag::create()
				->setTag("Information", CompoundTag::create()
					->setInt("Level", 1)
					->setInt("InvSize", 3)
					->setString("Type", $item->getType())
					->setString("Upgrades", "Null, Null, Null")
					->setString("Resources", "null")
					->setString("TargetId", $item->getVanillaName())

				);
		}

		$skinDataPath = match ($item->getVanillaName()) {
			"cobblestone" => "minion/cobblestone.png",
			"emerald ore" => "minion/emerald.png",
			"diamond ore" => "minion/diamond.png",
			"gold ore" => "minion/gold.png",
			"iron ore" => "minion/iron.png",
			"coal ore" => "minion/coal.png",
			"lapis lazuli ore" => "minion/lapis.png",
			"redstone ore" => "minion/redstone.png",
			"carrot" => "minion/carrot.png",
			"potato" => "minion/potato.png",
			"wheat seeds" => "minion/wheat.png",
			"melon" => "minion/melon.png",
			"pumpkin" => "minion/pumpkin.png",
			default => "minion/minion.png"
		};

		$skinData = Utils::createSkin(Skyblock::getInstance()->getDataFolder() . $skinDataPath);
		$skin = new Skin(
			"minion_" . $item->getVanillaName(),
			$skinData,
			"",
			"geometry.humanoid.custom",
			file_get_contents(Skyblock::getInstance()->getDataFolder() . "minion/minion.geo.json")
		);

		$entity = null;
		switch ($item->getType()) {
			case "Miner":
				$entity = new MinerMinion($location, $skin, $nbt);
				break;
			case "Farmer":
			    $entity = new FarmerMinion($location, $skin, $nbt);
				break;
		}

		$entity->spawnToAll();
	}
}