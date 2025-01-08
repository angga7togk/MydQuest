<?php

namespace angga7togk\mydquest\utils;

class Cache
{
  private static $cache = [];

  /**
   * Set a value in the cache with an optional expiration time.
   *
   * @param string $key
   * @param mixed $value
   * @param int|null $expiration Time in seconds until the cache expires (null for no expiration).
   */
  public static function set(string $key, $value, ?int $expiration = null): void
  {
    self::$cache[$key] = [
      'value' => $value,
      'expires_at' => $expiration ? time() + $expiration : null
    ];
  }

  /**
   * Get a value from the cache.
   *
   * @param string $key
   * @return mixed|null Returns the cached value, or null if the key does not exist or is expired.
   */
  public static function get(string $key)
  {
    if (self::has($key)) {
      return self::$cache[$key]['value'];
    }
    return null;
  }

  /**
   * Check if a key exists and is not expired in the cache.
   *
   * @param string $key
   * @return bool
   */
  public static function has(string $key): bool
  {
    if (isset(self::$cache[$key])) {
      $expires_at = self::$cache[$key]['expires_at'];
      if ($expires_at === null || $expires_at > time()) {
        return true;
      }

      // If the cache is expired, remove it
      unset(self::$cache[$key]);
    }
    return false;
  }

  /**
   * Remove a value from the cache.
   *
   * @param string $key
   */
  public static function delete(string $key): void
  {
    if (self::has($key)) unset(self::$cache[$key]);
  }

  /**
   * Clear all cached values.
   */
  public static function clear(): void
  {
    self::$cache = [];
  }
}
