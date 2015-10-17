<?php

namespace MoreTrees;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\level\generator\biome\Biome;
use pocketmine\level\generator\populator\TallGrass;
use pocketmine\block\Sapling;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\Item;
use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\utils\Random;
use pocketmine\block\Leaves;

class MoreTrees extends PluginBase implements Listener {
	public function onEnable() {
		$this->getServer ()->getPluginManager ()->registerEvents ( $this, $this );
		$this->register ();
	}
	public function register() {
		$forest = Biome::getBiome ( Biome::FOREST );
		$forest->addPopulator ( $this->getTree ( $forest::TYPE_BIRCH ? Sapling::BIRCH : Sapling::OAK, 5 ) );
		$forest->addPopulator ( $this->getGrass ( 1 ) );
		
		$mountains = Biome::getBiome ( Biome::MOUNTAINS );
		$mountains->addPopulator ( $this->getTree () );
		
		$ocean = Biome::getBiome ( Biome::OCEAN );
		$ocean->addPopulator ( $this->getTree () );
		
		$plain = Biome::getBiome ( Biome::PLAINS );
		$plain->addPopulator ( $this->getTree () );
		
		$river = Biome::getBiome ( Biome::RIVER );
		$river->addPopulator ( $this->getTree () );
		
		$smallMountain = Biome::getBiome ( Biome::SMALL_MOUNTAINS );
		$smallMountain->addPopulator ( $this->getTree () );
		
		$taiga = Biome::getBiome ( Biome::TAIGA );
		$taiga->addPopulator ( $this->getTree ( Sapling::OAK, 3 ) );
		
		$icePlains = Biome::getBiome ( Biome::ICE_PLAINS );
		$icePlains->addPopulator ( $this->getTree () );
	}
	public function onBlockBreakEvent(BlockBreakEvent $event) {
		if ($event->getBlock () instanceof Leaves)
			if (mt_rand ( 1, 4 ) === 1)
				$event->getBlock ()->getLevel ()->dropItem ( $event->getBlock (), Item::get ( Item::SAPLING, $event->getBlock ()->getDamage () & 0x03, 1 ) );
	}
	public function onSapling(BlockUpdateEvent $event) {
		if ($event->isCancelled ())
			return;
		if ($event->getBlock () instanceof Sapling)
			\pocketmine\level\generator\object\Tree::growTree ( $event->getBlock ()->getLevel (), $event->getBlock ()->x, $event->getBlock ()->y, $event->getBlock ()->z, new Random ( \mt_rand () ), $event->getBlock ()->getDamage () & 0x07 );
	}
	public function getTree($type = Sapling::OAK, $baseAmount = 1) {
		$tree = new Tree ( $type );
		$tree->setBaseAmount ( $baseAmount );
		return $tree;
	}
	public function getGrass($baseAmount) {
		$grass = new TallGrass ();
		$grass->setBaseAmount ( $baseAmount );
		return $grass;
	}
}

?>