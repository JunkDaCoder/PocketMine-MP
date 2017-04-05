<?php

namespace pocketmine\entity;

use pocketmine\block\Block;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item as ItemItem;
use pocketmine\level\particle\DestroyBlockParticle;
use pocketmine\network\protocol\AddPaintingPacket;
use pocketmine\Player;

class Painting extends Hanging{
	const NETWORK_ID = 83;
	
	const MOTIVES = [
		// Motive Width Height
		["Kebab", 1, 1],
		["Aztec", 1, 1],
		["Alban", 1, 1],
		["Aztec2", 1, 1],
		["Bomb", 1, 1],
		["Plant", 1, 1],
		["Wasteland", 1, 1],
		["Wanderer", 1, 2],
		["Graham", 1, 2],
		["Pool", 2, 1],
		["Courbet", 2, 1],
		["Sunset", 2, 1],
		["Sea", 2, 1],
		["Creebet", 2, 1],
		["Match", 2, 2],
		["Bust", 2, 2],
		["Stage", 2, 2],
		["Void", 2, 2],
		["SkullAndRoses", 2, 2],
		//array("Wither", 2, 2),
		["Fighters", 4, 2],
		["Skeleton", 4, 3],
		["DonkeyKong", 4, 3],
		["Pointer", 4, 4],
		["Pigscene", 4, 4],
		["Flaming Skull", 4, 4],
	];
	
	private $motive;
	protected $maxHealth = 1;

	public function initEntity(){
		parent::initEntity();
		
		if(isset($this->namedtag->Motive)){
			$this->motive = $this->namedtag["Motive"];
		}
		else{
			$this->close();
		}
	}

	public function getMotive(){
		return $this->motive;
	}

	public function attack($damage, EntityDamageEvent $source){
		parent::attack($damage, $source);
		if($source->isCancelled()) return false;
		$this->level->addParticle(new DestroyBlockParticle($this->add(0.5), Block::get(Block::LADDER)));
		$this->kill();
	}

	protected function updateMovement(){
		//Nothing to update, paintings cannot move.
	}

	public function spawnTo(Player $player){
		$pk = new AddPaintingPacket();
		$pk->eid = $this->getId();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->direction = $this->getDirection();
		$pk->title = $this->motive;
		$player->dataPacket($pk);
		parent::spawnTo($player);
	}

	public function getDrops(){
		return [ItemItem::get(ItemItem::PAINTING, 0, 1)];
	}
}
