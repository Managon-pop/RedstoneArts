<?php

namespace Managon;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\level\particle\RedstoneParticle;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\PluginTask;

class circle extends PluginBase implements Listener{
    public $tapper;
	public function onEnable(){
		Server::getInstance()->getPluginManager()->registerEvents($this,$this);
	}

	public function onCommand(CommandSender $sender, Command $c,$label,array $args){
		if(!$sender instanceof Player){
			$sender->sendMessage("Use the command in a game..");
			return;
		}else{
		if(count($args) < 1 || count($args) > 5){//feuture
			$sender->sendMessage("§bIf you make circle, Send /cir redius\n§aIf you make tornado, Send /trn redius height");
			return;
		}else{
		switch($c->getName()){
		case "cir":
    if(!isset($args[0])){
      $sender->sendMessage("Usage: /cir radius");
      return;
    }
           $hypo = (Int) $args[0];
           if($hypo < 10){
           	$sender->sendMessage("The radius must be bigger than 10");
           	return;
           }
           $this->tapper[$sender->getName()]["cir"] = $hypo;
           $sender->sendMessage("Tap the ground! I will make circle.");
           break;
        case "trn":
        if(!isset($args[0]) || !isset($args[1])){
          $sender->sendMessage("Usage: /trn radius height");
          return;
        }elseif(!isset($args[0]) and !isset($args[1])){
          $sender->sendMessage("Usage: /trn radius height");
          return;
        }
           $hypo = (Int) $args[0];
           if($hypo < 10){
           	$sender->sendMessage("The radius must be bigger than 10");
           	return;
           }
           $height = (Int) $args[1];
           if($height > 30){
           	$sender->sendMessage("The height must be lower than 30");
           	return;
           }
           $this->tapper[$sender->getName()]["trn"][$hypo] = $height;
           $sender->sendMessage("Tap the ground! I will make tornado.");
           break;
		}
}
}
}

    public function onTap(PlayerInteractEvent $event){
    	if(isset($this->tapper[$event->getPlayer()->getName()])){
    		$type = $this->tapper[$event->getPlayer()->getName()];
    		$block = $event->getBlock();
    		$x = $block->x;
    		$y = $block->y;
    		$z = $block->z;
    		$level = $event->getPlayer()->getLevel();
    foreach($type as $t => $hypo){
      switch($t){
        case "cir":
          $hypo = $type["cir"];
    	    for($r = 0; $r <= 1080; $r++){
    	    	$a = cos(deg2rad($r/3))* $hypo;
    	    	$b = sin(deg2rad($r/3))* $hypo;
    	    	$pos = new Vector3($x + $a, $y, $z + $b);
                $particle = new RedstoneParticle($pos, 8);
                $level->addParticle($particle);
    	    }
          unset($this->tapper[$event->getPlayer()->getName()]);
    	    break;
    	case "trn":
           foreach($hypo as $h => $height){
           $hh = $height*4;
           for($s = 1; $s <= 3600; $s++){
           	   $a = cos(deg2rad($s/2))* $h;//the Base
               $b = sin(deg2rad($s/2))* $h;//the highest
               $pos = new Vector3($x + $a, $y+$hh, $z+$b);
               $particle = new RedstoneParticle($pos, 20);
               $level->addParticle($particle,  $level->getPlayers());
           }
        unset($this->tapper[$event->getPlayer()->getName()]);
        break;
    	}
    }
}
}
}
}

