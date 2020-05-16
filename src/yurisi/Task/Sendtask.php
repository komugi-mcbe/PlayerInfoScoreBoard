<?php

namespace yurisi\Task;


use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

use pocketmine\scheduler\PluginTask;
use pocketmine\scheduler\CallbackTask;
use pocketmine\scheduler\Task;

use pocketmine\level\Level;

use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;

use onebone\economyapi\EconomyAPI;

use yurisi\main;
use yurisi\Data\PacketData;


class Sendtask extends Task implements PacketData{

	public function __construct(main $main){
		$this->Main = $main;
	}

	public function onRun($tick){
		foreach($this->Main->getServer()->getInstance()->getOnlinePlayers() as $player) {
			$name = $player->getName();
			if($this->Main->isOn($player)) {
				$this->RemoveData($player);
				$this->setupData($player);
				$this->sendData($player,"§a時刻 §7>§f ".date("G時i分"),1);
				$this->sendData($player,"§b座標 §7>§f §cX§f".$player->getfloorX()." §aY§f".$player->getfloorY()." §bZ§f".$player->getfloorZ(),2);
				$this->sendData($player,"§bワールド §7>§f ".$player->getLevel()->getName(),3);
				$this->sendData($player,"§e所持金 §7>§f ".EconomyAPI::getInstance()->myMoney($name)."§6K§eG",4);
				$this->sendData($player,"§cアイテムID §7>§f ".$player->getInventory()->getItemInHand()->getId().":".$player->getInventory()->getItemInHand()->getDamage(),5);
				$this->sendData($player,"§cオンライン人数 §7>§f ".count($player->getServer()->getOnlinePlayers())."/".$player->getServer()->getMaxPlayers(),6);
				$this->sendData($player,"§7@".$name,7);
			}else{
				$this->RemoveData($player);
			}

		}
	}

	function setupData(Player $player){
		$pk = new SetDisplayObjectivePacket();
		$pk->displaySlot = PacketData::D_S;
		$pk->objectiveName = PacketData::D_S;
		$pk->displayName = "§6Komugi§aNET";
		$pk->criteriaName = PacketData::C_N;
		$pk->sortOrder = 0;
		$player->sendDataPacket($pk);

	}

	function sendData(Player $player,String $data,Int $id){
		$entry = new ScorePacketEntry();
		$entry->objectiveName = PacketData::D_S;
		$entry->type = $entry::TYPE_FAKE_PLAYER;
		$entry->customName = $data;
		$entry->score = $id;
		$entry->scoreboardId = $id+11;

		$pk = new SetScorePacket();
		$pk->type = $pk::TYPE_CHANGE;
		$pk->entries[] = $entry;
		$player->sendDataPacket($pk);
	}

	function RemoveData(Player $player){
		$pk = new RemoveObjectivePacket();
		$pk->objectiveName = PacketData::D_S;
		$player->sendDataPacket($pk);
	}
}