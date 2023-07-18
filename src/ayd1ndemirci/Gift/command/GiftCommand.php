<?php

namespace ayd1ndemirci\Gift\command;

use ayd1ndemirci\Gift\form\GiftForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class GiftCommand extends Command
{

    public function __construct()
    {
        parent::__construct("hediye", "Hediye menüsü");
        $this->setAliases(["gift"]);
        $this->setPermission("gift.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender instanceof Player) return;
        if ($sender->getInventory()->getItemInHand()->getTypeId() === VanillaItems::AIR()->getTypeId()) {
            $sender->sendMessage("§8» §cEline bir eşya al.");
            return;
        }
        $sender->sendForm(new GiftForm($sender, $sender->getInventory()->getItemInHand()));
    }
}