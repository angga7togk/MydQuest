<?php

namespace angga7togk\mydquest\utils;

use DateTime;
use DateTimeZone;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

final class Utils
{

  /** Credit Code: https://github.com/fuyutsuki/Texter/blob/122f9b45a4896c51eb5b7f4fc0aa479ea0df56a7/src/jp/mcbe/fuyutsuki/Texter/Main.php#L223 */
  public static function unlinkRecursive(string $dir): bool
  {
    $files = array_diff(scandir($dir), [".", ".."]);
    foreach ($files as $file) {
      $path = $dir . DIRECTORY_SEPARATOR . $file;
      is_dir($path) ? self::unlinkRecursive($path) : unlink($path);
    }
    return rmdir($dir);
  }

  /** Credit Code: https://github.com/fuyutsuki/Texter/blob/122f9b45a4896c51eb5b7f4fc0aa479ea0df56a7/src/jp/mcbe/fuyutsuki/Texter/Main.php#L232 */
  public static function getFileExtension(string $path): string
  {
    $exploded = explode(".", $path);
    return $exploded[array_key_last($exploded)];
  }

  /**
   * Mengubah string ke array lore
   * untuk item biar mudah di baca
   */
  public static function translateStringToLore(string $s, string $prefix = "", string $suffix = ""): array
  {
    $words = preg_split('/\s+/', TextFormat::colorize($s));
    $lore = [];
    $line = '';

    foreach ($words as $word) {
      if ($word === "\n" || $word === '{line}') {
        if (!empty($line)) {
          $lore[] = TextFormat::colorize($prefix) . $line . TextFormat::colorize($suffix);
        }
        $lore[] = '';
        $line = '';
      } elseif (strlen($line . ' ' . $word) > 30) {
        $lore[] = TextFormat::colorize($prefix) . $line . TextFormat::colorize($suffix);
        $line = $word;
      } else {
        $line .= (empty($line) ? '' : ' ') . $word;
      }
    }

    if (!empty($line)) {
      $lore[] = TextFormat::colorize($prefix) . $line . TextFormat::colorize($suffix);
    }

    return $lore;
  }

  public static function getDate(?string $dateString = null): DateTime
  {
    $timezone = new DateTimeZone('Asia/Jakarta');
    return new DateTime($dateString ?? 'now', $timezone);
    // return $date->format('Y-m-d H:i:s');
  }

  /** Item to string, like 'minecraft:apple' */
  public static function itemToString(Item $item): string
  {
    if ($item->isNull()) return "";
    return StringToItemParser::getInstance()->lookupAliases($item)[0];
  }


  /**
   * @param string|Item $item or item name like 'minecraft:apple'
   * @return string like 'apple'
   */
  public static function getSuffixItemName(string|Item $item): string
  {
    if (is_string($item)) {
      if (str_contains($item, ":")) {
        $item = explode(":", $item)[1];
      }
      return $item;
    } else {
      return self::getSuffixItemName(self::itemToString($item));
    }
  }
}
