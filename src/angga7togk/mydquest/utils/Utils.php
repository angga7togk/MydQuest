<?php

namespace angga7togk\mydquestl\utils;

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
}
