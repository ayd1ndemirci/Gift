<?php

namespace ayd1ndemirci\Gift\manager;

use pocketmine\player\Player;
use pocketmine\Server;

class GiftManager
{
    public static function replaceParams(Player $player, string $itemName, int $count, string $note ,string $text): string
    {
        $placeholders = [
            "{player}" => $player->getName(),
            "{item}" => $itemName,
            "{count}" => $count,
            "{note}" => $note
        ];

        return strtr($text, $placeholders);
    }
}