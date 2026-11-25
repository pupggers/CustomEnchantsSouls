<?php

declare(strict_types=1);

namespace pup\souls;

use pocketmine\player\Player;

trait SoulCostTrait
{
	private int $soulCost = 0;

	public function setSoulCost(int $cost): void
	{
		$this->soulCost = max(0, $cost);
	}

	public function getSoulCost(): int
	{
		return $this->soulCost;
	}

	protected function paySouls(Player $player): bool
	{
		return Souls::pay($player, $this->soulCost);
	}
}
