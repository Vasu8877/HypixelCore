<?php

namespace Biswajit\Core\Entitys\Minion;

use pocketmine\block\Block;
use pocketmine\inventory\SimpleInventory;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\Tag;

trait MinionHandler {
  
  /** @var Tag|CompoundTag */
  public Tag|CompoundTag $minionInfo;

  /** @var Int */
  private int $invSize;

  /** @var Int */
  private int $level;

  /** @var ?SimpleInventory */
  public ?SimpleInventory $minionInv = null;

  /** @var array */
  private array $Upgrades;

  private int $currentTick = 0;

  protected bool $canWork = true;

  /** @var Block|Null */
  public ?Block $target = null;

  public const ARMOR = array(
    "cobblestone" => array(255, 255, 255),
    "coal ore" => array(0, 0, 0),
    "gold ore" => array(255, 238, 88),
    "iron ore" => array(224, 224, 224),
    "lapis lazulie ore" => array(13, 71, 161),
    "diamond ore" => array(102, 255, 255),
    "emerald ore" => array(102, 255, 102),
    "redstone ore" => array(244, 67, 54)
    );

  public function getInvSize(int $level): int {
    if ($level === 1) $size = 3;
    if ($level === 2) $size = 5;
    if ($level === 3) $size = 8;
    if ($level === 4) $size = 11;
    if ($level === 5) $size = 15;
    return $size;
  }

  public function getTargetId(): string {
	  return $this->minionInfo->getString("TargetId");
  }

  public function getType(): string {
	  return $this->minionInfo->getString("Type");
  }


  public function getUpgrades(): array {
    return $this->Upgrades;
  }

  public function getMinionInventory(): SimpleInventory {
    return $this->minionInv;
  }
  
  private function setUpgrade(string $upgrade, int $num): void {
    $Upgrades = $this->getUpgrades();
    if($num === 1)
    {
      $this->Upgrades = array($upgrade, $Upgrades[1], $Upgrades[2]);
    }elseif($num === 2)
    {
      $this->Upgrades = array($Upgrades[0], $upgrade, $Upgrades[2]);
    }elseif($num === 3)
    {
      $this->Upgrades = array($Upgrades[0], $Upgrades[1], $upgrade);
    }
  }
  
  private function hasUpgrade(string $upgrade): bool {
    $Upgrades = $this->getUpgrades();
    if(in_array($upgrade, $Upgrades))
    {
      return true;
    }else{
      return false;
    }
  }
  
  public function getInventorySize(): int {
    return $this->invSize;
  }
  
  public function setInventorySize(int $size): void {
    $this->invSize = $size;
  }

  public function getLevel(): int {
    return $this->level;
  }

  public function setLevel(int $level): void {
    $this->level = $level;
  }

   public function getSpeedInTicks(): int {
    return $this->getSpeedInSeconds($this->getLevel()) * 20;
  }

  public function getSpeedInSeconds(int $level): int {
    if ($level === 1) $speed = 35;
    if ($level === 2) $speed = 30;
    if ($level === 3) $speed = 25;
    if ($level === 4) $speed = 20;
    if ($level === 5) $speed = 15;
    return $speed;
  }

  public function onTick(): void {}
  public function setUp(): void {}

	/**
	 * @return Item
	 */
  public function getEgg(): Item {
	  return $this->getEgg();
	}
  
}