<?php

declare(strict_types=1);

namespace pup\souls;

/** An enchant that spends souls. Its cost comes from `soul_cost` in the owning plugin's enchantments.json. */
interface SoulPowered
{
	public function setSoulCost(int $cost): void;

	public function getSoulCost(): int;
}
