<?php

namespace angga7togk\mydquest\quest;

use angga7togk\mydquest\utils\Utils;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;

class JsonQuest extends Quest implements Listener
{

  /** @priority MONITOR */
  public function onBreak(BlockBreakEvent $event)
  {
    $actions = $this->getActions();
    $player = $event->getPlayer();
    if (!isset($actions['break'])) return;

    $this->preRunProgress($player, function (ProgressQuest $progressQuest) use ($event, &$player, $actions) {
      $condition = $actions['break']['condition'];
      $itemHand = Utils::getSuffixItemName($player->getInventory()->getItemInHand());
      $blockTarget = Utils::getSuffixItemName($event->getBlock()->asItem());

      $processedTargetBlock = $this->processTargetBlock($condition['target_block'] ?? []);
      $processedItemInHand = $this->processTargetBlock($condition['item_in_hand'] ?? []);

      $targetBlockValid = is_array($processedTargetBlock)
        ? in_array($blockTarget, (array)$processedTargetBlock)
        : ($blockTarget === $processedTargetBlock);

      $itemHandValid = is_array($processedItemInHand)
        ? in_array($itemHand, (array)$processedItemInHand)
        : ($itemHand === $processedItemInHand);

      if ($condition['operator'] == "and") {
        if ($targetBlockValid && $itemHandValid) {
          $progressQuest->runProgress($player, (int)$actions['break']['add_progress']);
        }
      } elseif ($condition['operator'] == "or" || $condition['operator'] == "single") {
        if ($targetBlockValid || $itemHandValid) {
          $progressQuest->runProgress($player, (int)$actions['break']['add_progress']);
        }
      }
    });
  }


  /** @priority MONITOR */
  public function onPlace(BlockPlaceEvent $event)
  {
    $actions = $this->getActions();
    $player = $event->getPlayer();
    if (!isset($actions['place'])) return;

    $this->preRunProgress($player, function (ProgressQuest $progressQuest) use ($event, &$player, $actions) {
      $condition = $actions['place']['condition'];
      $itemHand = Utils::getSuffixItemName($event->getItem());
      $blockTarget = Utils::getSuffixItemName($event->getBlockAgainst());

      $processedTargetBlock = $this->processTargetBlock($condition['target_block'] ?? []);
      $processedItemInHand = $this->processTargetBlock($condition['item_in_hand'] ?? []);

      $targetBlockValid = is_array($processedTargetBlock)
        ? in_array($blockTarget, (array)$processedTargetBlock)
        : ($blockTarget === $processedTargetBlock);

      $itemHandValid = is_array($processedItemInHand)
        ? in_array($itemHand, (array)$processedItemInHand)
        : ($itemHand === $processedItemInHand);

        if ($condition['operator'] == "and") {
          if ($targetBlockValid && $itemHandValid) {
            $progressQuest->runProgress($player, (int)$actions['place']['add_progress']);
          }
        } elseif ($condition['operator'] == "or" || $condition['operator'] == "single") {
          if ($targetBlockValid || $itemHandValid) {
            $progressQuest->runProgress($player, (int)$actions['place']['add_progress']);
          }
        }
    });
  }


  public function processTargetBlock(array|string $target): array|string
  {
    if (is_array($target)) {
      return array_map(fn($item) => Utils::getSuffixItemName($item), $target);
    } elseif (is_string($target)) {
      return Utils::getSuffixItemName($target);
    }
    return $target;
  }
}
