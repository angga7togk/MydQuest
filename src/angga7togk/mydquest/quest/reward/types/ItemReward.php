<?php

namespace angga7togk\mydquest\quest\reward\types;

use angga7togk\mydquest\MydQuest;
use angga7togk\mydquest\quest\reward\Reward;
use DaPigGuy\PiggyCustomEnchants\CustomEnchantManager;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;

class ItemReward extends Reward
{

  public function getValue(): ?Item
  {
    if ($this->value instanceof Item) return $this->value;
    if (is_array($this->value)) return $this->arrayToItem($this->value);
    return null;
  }

  private function arrayToItem(array $d): Item
  {
    $item = StringToItemParser::getInstance()->parse($d['item']);
    $item->setCount(isset($d['count']) ? $d['count'] : 1);
    if(isset($d['custom_name'])) $item->setCustomName($d['custom_name']);
    if(isset($d['lore']) && is_array($d['lore'])) $item->setLore($d['lore']);
    if(isset($d['enchantments'])) {
      foreach ($d['enchantments'] as $enchantment) {
        $ee = explode(':', $enchantment);
        $enchant = $this->getEnchantmentByName($ee[0]);
        if ($enchant !== null && isset($ee[1]) && is_numeric($ee[1])) {
          $enchantInstance = new EnchantmentInstance($enchant, (int) $ee[1]);
          $item->addEnchantment($enchantInstance);
        }
      }
    }
    return $item;
  }

  private function getEnchantmentByName(string $enchantName): ?Enchantment
  {
    $enchant = null;
    if (MydQuest::$piggyCustomEnchantmentsSupported) {
      if (CustomEnchantManager::getEnchantmentByName($enchantName) !== null) {
        $enchant = CustomEnchantManager::getEnchantmentByName($enchantName);
      }
    }
    $vanillaEnchantments = VanillaEnchantments::getAll();
    if ($enchant === null && isset($vanillaEnchantments[$enchantName])) {
      $enchant = $vanillaEnchantments[$enchantName];
    }
    return $enchant;
  }
}
