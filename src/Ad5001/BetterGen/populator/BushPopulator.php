<?php

/*
 * BushPopulator from BetterGen
 * Copyright (C) Ad5001 2017
 * Licensed under the BoxOfDevs Public General LICENSE which can be found in the file LICENSE in the root directory
 * @author ad5001
 */
namespace Ad5001\BetterGen\populator;

use pocketmine\utils\Random;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use Ad5001\BetterGen\populator\TreePopulator;
use Ad5001\BetterGen\populator\AmountPopulator;
use Ad5001\BetterGen\structure\Bush;

class BushPopulator extends AmountPopulator {
	protected $level;
	protected $type;
	
	/*
	 * Constructs the class
	 * @param $type int
	 */
	public function __construct($type = 0) {
		$this->type = $type;
	}
	
	/*
	 * Populate the chunk
	 * @param $level pocketmine\level\ChunkManager
	 * @param $chunkX int
	 * @param $chunkZ int
	 * @param $random pocketmine\utils\Random
	 */
	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		$amount = $this->getAmount($random);
		for($i = 0; $i < $amount; $i++) {
			$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
			$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);
			$y = $this->getHighestWorkableBlock($x, $z);
			if ($y === -1) {
				continue;
			}
			$tree = new TreePopulator::$types [$this->type] ();
			$bush = new Bush($tree->leafBlock, $tree->leafType ?? $tree->type);
			$bush->placeObject($level, $x, $y, $z, $random);
		}
	}
	
	/*
	 * Gets the top block (y) on an x and z axes
	 * @param $x int
	 * @param $z int
	 */
	protected function getHighestWorkableBlock($x, $z) {
		for($y = 127; $y > 0; -- $y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b === Block::DIRT or $b === Block::GRASS or $b === Block::PODZOL) {
				break;
			} elseif ($b !== 0 and $b !== Block::SNOW_LAYER) {
				return - 1;
			}
		}
		
		return $y++;
	}
}