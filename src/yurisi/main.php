<?php

namespace yurisi;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;

use yurisi\Task\Sendtask;
use yurisi\Command\infoCommand;

class main extends PluginBase implements Listener {

   public $plugin= "ScoreBoardStatus";

   public function onEnable() {
	$this->getServer()->getPluginManager()->registerEvents($this, $this);
	$this->getScheduler()->scheduleRepeatingTask(new Sendtask($this), 40);
	$this->getServer()->getCommandMap()->register("info", new infoCommand($this));
	$this->getLogger()->info("§b".$this->plugin."を開きました");
   }


   public function isOn(Player $player){
	   $tag = $player->namedtag;
	   if ($tag->offsetExists($this->plugin)) {
		   if ($tag->getInt($this->plugin) == 0) {
			   return true;
		   } else {
			   return false;
		   }
	   }else{
	   	return true;
	   }
   }

   public function onDisable() {
		$this->getLogger()->info("§a".$this->plugin."を閉じました");
   }

}