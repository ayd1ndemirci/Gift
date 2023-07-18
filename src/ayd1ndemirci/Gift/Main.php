<?php

namespace ayd1ndemirci\Gift;

use ayd1ndemirci\Gift\command\GiftCommand;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{

    public static Main $main;

    protected function onLoad(): void
    {
        self::$main = $this;
        $this->saveDefaultConfig();
    }

    protected function onEnable(): void
    {
        $this->getServer()->getCommandMap()->register("hediye", new GiftCommand());
    }

    public static function getInstance(): self
    {
        return self::$main;
    }
}