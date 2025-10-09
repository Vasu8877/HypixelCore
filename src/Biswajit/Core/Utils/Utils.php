<?php

declare(strict_types = 1);

namespace Biswajit\Core\Utils;

use Biswajit\Core\Skyblock;

class Utils {

    public const BT_MARK = "§d» §";

    public static function getVersion(): string {
      return Skyblock::getInstance()->getDescription()->getVersion();
    }
    
    public static function getServerName(): string {
      return Skyblock::getInstance()->getConfig()->get("SERVER-NAME");
    }

    public static function changeNumericFormat(?int $number, string $format) {

    if ($number === null) {

        return;

    }
    if($format === "k")
    {
      $numeric = $number/1000;
      $data = $numeric."k";
      return $data;
    }
    if($format === "time")
    {
      $secs = (int)$number;
        if($secs === 0)
        {
          return '0 secs';
        }
        $mins  = 0;
        $hours = 0;
        $days  = 0;
        $weeks = 0;
        if($secs >= 60)
        {
          $mins = (int)($secs / 60);
          $secs = $secs % 60;
        }
        if($mins >= 60)
        {
          $hours = (int)($mins / 60);
          $mins = $mins % 60;
        }
        if($hours >= 24)
        {
          $days = (int)($hours / 24);
          $hours = $hours % 60;
        }
        if($days >= 7)
        {
          $weeks = (int)($days / 7);
          $days = $days % 7;
        }
        
        $result = '';
        if($weeks)
        {
            $result .= "$weeks weeks ";
        }
        if($days)
        {
            $result .= "$days days ";
        }
        if($hours)
        {
            $result .= "$hours hours ";
        }
        if($mins)
        {
            $result .= "$mins mins ";
        }
        if($secs)
        {
            $result .= "$secs secs ";
        }
        $result = rtrim($result);
        return $result;
    }
  }
}