<?php

namespace ayd1ndemirci\Gift\event;

use ayd1ndemirci\Gift\Main;
use ayd1ndemirci\Gift\manager\GiftManager;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\event\Event;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\Server;

class PlayerGiftEvent extends Event
{
    public Player $player;

    public Player $selectedPlayer;

    public Item $item;

    public int $count;

    public string $note;

    public function __construct(Player $player, Player $selectedPlayer, Item $item, int $count, string $note)
    {
        $this->player = $player;
        $this->selectedPlayer = $selectedPlayer;
        $this->item = $item;
        $this->count = $count;
        $this->note = $note;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return Player
     */
    public function getSelectedPlayer(): Player
    {
        return $this->selectedPlayer;
    }

    /**
     * @return int
     */

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return string
     */
    public function getNote(): string
    {
        return $this->note;
    }

    public function call(): void
    {
        $this->getPlayer()->getInventory()->removeItem($this->getItem());

        $this->getSelectedPlayer()->sendMessage("§8» §2§o{$this->getPlayer()->getName()} §r§aadlı oyuncu sana §2§o{$this->getCount()}x {$this->getItem()->getName()} §r§agönderdi.");

        if (!$this->getSelectedPlayer()->getInventory()->canAddItem($this->getItem())) {
            $this->getSelectedPlayer()->getWorld()->dropItem($this->getSelectedPlayer()->getPosition(), $this->getItem());
            $this->getSelectedPlayer()->sendMessage("§8» §cEnvanterin dolu olduğu için hediye yere düştü.");
            return;
        }

        $this->getSelectedPlayer()->getInventory()->addItem($this->getItem());


        $this->getSelectedPlayer()->sendForm(new MenuForm(
            GiftManager::replaceParams($this->getPlayer(), $this->getItem()->getName(), $this->getCount(), $this->getNote(), Main::getInstance()->getConfig()->get("Menu")["Gift-Take-Form"]["Title"]),
            GiftManager::replaceParams($this->getPlayer(), $this->getItem()->getName(), $this->getCount(), $this->getNote(), Main::getInstance()->getConfig()->get("Menu")["Gift-Take-Form"]["Content"]),
            [
                new MenuOption("Tamam")
            ],
            function (Player $player, int $data): void {}
        ));
    }
}