<?php

declare(strict_types = 1);

namespace Biswajit\Core\Utils;

use Biswajit\Core\Player;
use Biswajit\Core\Skyblock;
use pocketmine\item\Item;
use pocketmine\math\Vector3;

class Utils {

    public static function getVersion(): string {
      return Skyblock::getInstance()->getDescription()->getVersion();
    }
    
    public static function getServerName(): string {
      return Skyblock::getInstance()->getConfig()->get("SERVER-NAME");
    }

    public static function changeNumericFormat(?int $number, string $format): ?string {

    if ($number !== null) {
		if ($format === "k") {
			$numeric = $number / 1000;
			$data = $numeric . "k";
			return $data;
		}
		if ($format === "time") {
			$secs = (int)$number;
			if ($secs === 0) {
				return '0 secs';
			}
			$mins = 0;
			$hours = 0;
			$days = 0;
			$weeks = 0;
			if ($secs >= 60) {
				$mins = (int)($secs / 60);
				$secs = $secs % 60;
			}
			if ($mins >= 60) {
				$hours = (int)($mins / 60);
				$mins = $mins % 60;
			}
			if ($hours >= 24) {
				$days = (int)($hours / 24);
				$hours = $hours % 60;
			}
			if ($days >= 7) {
				$weeks = (int)($days / 7);
				$days = $days % 7;
			}

			$result = '';
			if ($weeks) {
				$result .= "$weeks weeks ";
			}
			if ($days) {
				$result .= "$days days ";
			}
			if ($hours) {
				$result .= "$hours hours ";
			}
			if ($mins) {
				$result .= "$mins mins ";
			}
			if ($secs) {
				$result .= "$secs secs ";
			}
			return rtrim($result);
		}
	 }
	return null;
  }

    public static function parseDuration(string $duration): int {
        $duration = strtolower($duration);
        $value = (int)substr($duration, 0, -1);
        $unit = substr($duration, -1);

        switch ($unit) {
            case 'd':
                return $value * 86400;
            case 'h':
                return $value * 3600;
            case 'm':
                return $value * 60;
            case 's':
                return $value;
            default:
                return 0;
        }
    }

	public static function getRomanNumeral(int $integer): string
	{
		$romanNumeralConversionTable = [
			'M' => 1000,
			'CM' => 900,
			'D' => 500,
			'CD' => 400,
			'C' => 100,
			'XC' => 90,
			'L' => 50,
			'XL' => 40,
			'X' => 10,
			'IX' => 9,
			'V' => 5,
			'IV' => 4,
			'I' => 1,
		];
		$romanString = '';
		while ($integer > 0) {
			foreach ($romanNumeralConversionTable as $rom => $arb) {
				if ($integer >= $arb) {
					$integer -= $arb;
					$romanString .= $rom;

					break;
				}
			}
		}

		return $romanString;
	}

	public static function giveItems(Player $player, Item $items): void
	{
		if ($player->getInventory()->canAddItem($items)) {
			$player->getInventory()->addItem($items);
			return;
		}

		$world = $player->getWorld();
		$pos = $player->getPosition();
		$x = $pos->getX();
		$y = $pos->getY();
		$z = $pos->getZ();
		$world->dropItem(new Vector3($x, $y, $z), $items);
	}

	public static function createSkin(string $path): string {
      $image = @imagecreatefrompng($path);
      $bytes = '';
      $imageSize = (int) @getimagesize($path)[1];
     for($y = 0; $y < $imageSize; $y++) {
      for($x = 0; $x < $imageSize; $x++) {
        $colorAt = @imagecolorat($image, $x, $y);
        $a = ((~((int)($colorAt >> 24))) << 1) & 0xff;
        $r = ($colorAt >> 16) & 0xff;
        $g = ($colorAt >> 8) & 0xff;
        $b = $colorAt & 0xff;
        $bytes .= chr($r) . chr($g) . chr($b) . chr($a);
      }
    }
    @imagedestroy($image);
    return $bytes;
  }
}