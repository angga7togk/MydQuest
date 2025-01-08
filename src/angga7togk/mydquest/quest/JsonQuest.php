<?php

namespace angga7togk\mydquest\quest;

use angga7togk\mydquest\utils\Utils;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class JsonQuest extends Quest implements Listener
{

  /** @priority MONITOR */
  public function onBreak(BlockBreakEvent $event)
  {
    $actions = $this->getActions();
    $player = $event->getPlayer();
    if (!isset($actions['break'])) {
      return;
    }

    $db = $this->getLoader()->getDatabase();
    $db->getPlayerOne(
      $player,
      $this->getId(),
      function ($pq, $rows) use (&$player, $event, $db, $actions) {
        if ($pq?->isActive() !== true) return;

        $player->sendMessage("masuk ke database Quest active");

        // Cek kondisi break
        $condition = $actions['break']['condition'];
        if (isset($condition['and'])) {
          $c = $condition['and'];

          $player->sendMessage("masuk ke database actions break 'and'");
          // Ambil item yang dipegang dan blok yang dihancurkan
          $itemHand = Utils::getSuffixItemName($player->getInventory()->getItemInHand());
          $blockTarget = Utils::getSuffixItemName($event->getBlock()->asItem());

          $player->sendMessage("ItemHand: $itemHand");
          $player->sendMessage("BlockTarget: $blockTarget");
          // Fungsi untuk memproses array atau string

          // Proses target block dan item di tangan
          $processedTargetBlock = $this->processTargetBlock($c['target_block']);
          $processedItemInHand = $this->processTargetBlock($c['item_in_hand']);

          // Validasi target block dan item di tangan
          $targetBlockValid = is_array($processedTargetBlock)
            ? in_array($blockTarget, $processedTargetBlock)
            : ($blockTarget === $processedTargetBlock);

          $itemHandValid = is_array($processedItemInHand)
            ? in_array($itemHand, $processedItemInHand)
            : ($itemHand === $processedItemInHand);


          // Pastikan kedua kondisi terpenuhi (AND)
          if ($targetBlockValid && $itemHandValid) {
            $player->sendMessage('And condition valid');
            // Jika kondisi terpenuhi, tambahkan progress
            // $pq->addProgress(1);
            // $this->getLoader()->getDatabase()->setProgress($player, $this->getId(), $pq->getQuestId(), $pq->getProgress());
          }
        }
      }
    );
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
