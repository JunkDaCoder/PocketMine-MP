<?php

namespace pocketmine\entity;

use pocketmine\item\Potion;
use pocketmine\level\Level;
use pocketmine\level\particle\SpellParticle;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\Player;

class ThrownPotion extends Projectile {
	const NETWORK_ID = 86;
	const DATA_POTION_ID = 16;//TODO: update this
	public $width = 0.25;
	public $length = 0.25;
	public $height = 0.25;
	protected $gravity = 0.1;
	protected $drag = 0.05;

	public function __construct(Level $level, CompoundTag $nbt, Entity $shootingEntity = null) {
		if (!isset($nbt->PotionId)) {
			$nbt->PotionId = new ShortTag("PotionId", Potion::AWKWARD);
		}
		parent::__construct($level, $nbt, $shootingEntity);
		unset($this->dataProperties[self::DATA_SHOOTER_ID]);
		$this->setDataProperty(self::DATA_POTION_ID, self::DATA_TYPE_SHORT, $this->getPotionId());
	}

	public function getPotionId() {
		return $this->namedtag["PotionId"];
	}

	public function onUpdate($currentTick) {
		if ($this->closed) {
			return false;
		}
		$this->timings->startTiming();
		$hasUpdate = parent::onUpdate($currentTick);
		$this->age++;
		if ($this->age > 1200 or $this->isCollided) {
			$this->kill();
			$this->close();
			$hasUpdate = true;
		}
		$this->timings->stopTiming();
		return $hasUpdate;
	}

	public function kill() {
		if ($this->isAlive()) {
			$color = Potion::getColor($this->getPotionId());
			$this->getLevel()->addParticle(new SpellParticle($this, $color[0], $color[1], $color[2]));
			$players = $this->getViewers();
			foreach ($players as $p) {
				if ($p->distance($this) <= 6) {
					switch ($this->getPotionId()) {
						case Potion::NIGHT_VISION:
							$p->addEffect(Effect::getEffect(Effect::NIGHT_VISION)->setAmplifier(0)->setDuration(3 * 60 * 20));
							break;
						case Potion::NIGHT_VISION_T:
							$p->addEffect(Effect::getEffect(Effect::NIGHT_VISION)->setAmplifier(0)->setDuration(8 * 60 * 20));
							break;
						case Potion::INVISIBILITY:
							$p->addEffect(Effect::getEffect(Effect::INVISIBILITY)->setAmplifier(0)->setDuration(3 * 60 * 20));
							break;
						case Potion::INVISIBILITY_T:
							$p->addEffect(Effect::getEffect(Effect::INVISIBILITY)->setAmplifier(0)->setDuration(8 * 60 * 20));
							break;
						case Potion::LEAPING:
							$p->addEffect(Effect::getEffect(Effect::JUMP)->setAmplifier(0)->setDuration(3 * 60 * 20));
							break;
						case Potion::LEAPING_T:
							$p->addEffect(Effect::getEffect(Effect::JUMP)->setAmplifier(0)->setDuration(8 * 60 * 20));
							break;
						case Potion::LEAPING_TWO:
							$p->addEffect(Effect::getEffect(Effect::JUMP)->setAmplifier(1)->setDuration(1.5 * 60 * 20));
							break;
						case Potion::FIRE_RESISTANCE:
							$p->addEffect(Effect::getEffect(Effect::FIRE_RESISTANCE)->setAmplifier(0)->setDuration(3 * 60 * 20));
							break;
						case Potion::FIRE_RESISTANCE_T:
							$p->addEffect(Effect::getEffect(Effect::FIRE_RESISTANCE)->setAmplifier(0)->setDuration(8 * 60 * 20));
							break;
						case Potion::SPEED:
							$p->addEffect(Effect::getEffect(Effect::SPEED)->setAmplifier(0)->setDuration(3 * 60 * 20));
							break;
						case Potion::SPEED_T:
							$p->addEffect(Effect::getEffect(Effect::SPEED)->setAmplifier(0)->setDuration(8 * 60 * 20));
							break;
						case Potion::SPEED_TWO:
							$p->addEffect(Effect::getEffect(Effect::SPEED)->setAmplifier(1)->setDuration(1.5 * 60 * 20));
							break;
						case Potion::SLOWNESS:
							$p->addEffect(Effect::getEffect(Effect::SLOWNESS)->setAmplifier(0)->setDuration(1 * 60 * 20));
							break;
						case Potion::SLOWNESS_T:
							$p->addEffect(Effect::getEffect(Effect::SLOWNESS)->setAmplifier(0)->setDuration(4 * 60 * 20));
							break;
						case Potion::WATER_BREATHING:
							$p->addEffect(Effect::getEffect(Effect::WATER_BREATHING)->setAmplifier(0)->setDuration(3 * 60 * 20));
							break;
						case Potion::WATER_BREATHING_T:
							$p->addEffect(Effect::getEffect(Effect::WATER_BREATHING)->setAmplifier(0)->setDuration(8 * 60 * 20));
							break;
						case Potion::POISON:
							if ($p->isSurvival()) {
								$p->addEffect(Effect::getEffect(Effect::POISON)->setAmplifier(0)->setDuration(45 * 20));
							}
							break;
						case Potion::POISON_T:
							if ($p->isSurvival()) {
								$p->addEffect(Effect::getEffect(Effect::POISON)->setAmplifier(0)->setDuration(2 * 60 * 20));
							}
							break;
						case Potion::POISON_TWO:
							if ($p->isSurvival()) {
								$p->addEffect(Effect::getEffect(Effect::POISON)->setAmplifier(0)->setDuration(22 * 20));
							}
							break;
						case Potion::REGENERATION:
							if ($p->isSurvival()) {
								$p->addEffect(Effect::getEffect(Effect::REGENERATION)->setAmplifier(0)->setDuration(45 * 20));
							}
							break;
						case Potion::REGENERATION_T:
							if ($p->isSurvival()) {
								$p->addEffect(Effect::getEffect(Effect::REGENERATION)->setAmplifier(0)->setDuration(2 * 60 * 20));
							}
							break;
						case Potion::REGENERATION_TWO:
							if ($p->isSurvival()) {
								$p->addEffect(Effect::getEffect(Effect::REGENERATION)->setAmplifier(1)->setDuration(22 * 20));
							}
							break;
						case Potion::STRENGTH:
							$p->addEffect(Effect::getEffect(Effect::STRENGTH)->setAmplifier(0)->setDuration(3 * 60 * 20));
							break;
						case Potion::STRENGTH_T:
							$p->addEffect(Effect::getEffect(Effect::STRENGTH)->setAmplifier(0)->setDuration(8 * 60 * 20));
							break;
						case Potion::STRENGTH_TWO:
							$p->addEffect(Effect::getEffect(Effect::STRENGTH)->setAmplifier(1)->setDuration(1.5 * 60 * 20));
							break;
						case Potion::WEAKNESS:
							$p->addEffect(Effect::getEffect(Effect::WEAKNESS)->setAmplifier(0)->setDuration(1.5 * 60 * 20));
							break;
						case Potion::WEAKNESS_T:
							$p->addEffect(Effect::getEffect(Effect::WEAKNESS)->setAmplifier(0)->setDuration(4 * 60 * 20));
							break;
						case Potion::HEALING:
							if ($p->isSurvival()) {
								$p->addEffect(Effect::getEffect(Effect::HEALING)->setAmplifier(0)->setDuration(1));
							}
							break;
						case Potion::HEALING_TWO:
							if ($p->isSurvival()) {
								$p->addEffect(Effect::getEffect(Effect::HEALING)->setAmplifier(1)->setDuration(1));
							}
							break;
						case Potion::HARMING:
							if ($p->isSurvival()) {
								$p->addEffect(Effect::getEffect(Effect::HARMING)->setAmplifier(0)->setDuration(1));
							}
							break;
						case Potion::HARMING_TWO:
							if ($p->isSurvival()) {
								$p->addEffect(Effect::getEffect(Effect::HARMING)->setAmplifier(1)->setDuration(1));
							}
							break;
					}
				}
			}
			parent::kill();
		}
	}

	public function spawnTo(Player $player) {
		$pk = new AddEntityPacket();
		$pk->type = self::NETWORK_ID;
		$pk->eid = $this->getId();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->metadata = $this->dataProperties;
		$player->dataPacket($pk);

		parent::spawnTo($player);
	}
}
