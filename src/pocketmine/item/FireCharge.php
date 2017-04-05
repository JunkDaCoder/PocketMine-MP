<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\Fire;
use pocketmine\block\Solid;
use pocketmine\level\Level;
use pocketmine\Player;

class FireCharge extends Item {
	public function __construct($meta = 0, $count = 1) {
		parent::__construct(self::FIRE_CHARGE, $meta, $count, "Fire Charge");
	}

	public function canBeActivated() {
		return true;
	}

	public function onActivate(Level $level, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz) {
		if ($block->getId() === self::AIR and ($target instanceof Solid)) {
			$level->setBlock($block, new Fire(), true);
			$this->useOn($block);

			return true;
		}

		return false;
	}

	public function useOn($object) {
		if ($object instanceof Block) {
			$this->count--;
			return true;
		} else return false;
	}
}

