<?php

namespace yurisi\Command;

use pocketmine\Player;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use yurisi\main;

class infoCommand extends Command {

	public function __construct(main $main) {
		$this->main = $main;
		parent::__construct("info", "ステータスの表示切り替え", "/info");
	}

	public function execute(CommandSender $sender, string $label, array $args) {
		if ($sender instanceof Player) {
			$tag = $sender->namedtag;
			if ($this->main->isOn($sender) === false) {
				$tag->setInt($this->main->plugin, 0);
				$sender->sendMessage("§a >> §fステータス表示を§aON§fにしました");
				return true;
			} else {
				$tag->setInt($this->main->plugin, 1);
				$sender->sendMessage("§a >> §fステータス表示を§cOFF§fにしました");
				return true;
			}
		}
	}
}