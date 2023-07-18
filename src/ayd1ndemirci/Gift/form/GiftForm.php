<?php

namespace ayd1ndemirci\Gift\form;

use ayd1ndemirci\Gift\event\PlayerGiftEvent;
use ayd1ndemirci\Gift\manager\GiftManager;
use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Dropdown;
use dktapps\pmforms\element\Input;
use dktapps\pmforms\element\Label;
use dktapps\pmforms\element\Slider;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\Server;

class GiftForm extends CustomForm
{

    /**
     * @param Player $player
     * @param Item $item
     */

    public array $list = [];

    public function __construct(Player $player, Item $item)
    {

        $itemName = $item->getName();

        $count = $item->getCount();


        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            if ($player->getName() !== $onlinePlayer->getName()) {
                $this->list[] = $onlinePlayer->getName();
            }
        }
        sort($this->list);


        parent::__construct(
            "Hediye Menüsü",
            [
                new Label("\n", "\n"),
                new Label("info0", "§aElindeki eşya: §2§o{$itemName}"),
                new Dropdown("player", "\nOyuncu Seç", $this->list),
                new Slider("count", "\nMiktar", 1, $count),
                new Input("note", "\nNot  (opsiyonel):", "Benden bir hediye")
            ],
            function (Player $player, CustomFormResponse $response) use ($item): void
            {
                $selected = $this->list[$response->getInt("player")];

                $selectedPlayer = Server::getInstance()->getPlayerExact($selected);

                if (!$selectedPlayer instanceof Player) {
                    $player->sendMessage("§8» §4§o{$selected} §r§cadlı oyuncu oyundan ayrılmış.");
                    return;
                }

                if ($item->getTypeId() !== $player->getInventory()->getItemInHand()->getTypeId()) {
                    $player->sendMessage("§8» §cKomut yazdığın eşya ile göndereceğin eşya değiştirilmiş.");
                    return;
                }

                $item->setCount($response->getFloat("count"));

                $note = empty($response->getString("note")) ? "§cNot girilmemiş" : $response->getString("note");

                $event = new PlayerGiftEvent($player, $selectedPlayer, $item, $response->getFloat("count"), $note);
                $event->call();

                $player->sendMessage("§8» §2§o{$selected} §r§aadlı oyuncuya hediye gönderildi.");

            }
        );
    }
}