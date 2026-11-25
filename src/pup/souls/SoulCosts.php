<?php

declare(strict_types=1);

namespace pup\souls;

use JsonException;
use pocketmine\plugin\PluginBase;

final class SoulCosts
{
	/** @param array<string, object> $registered what CustomEnchantsAPI::registerAll() returned */
	public static function apply(PluginBase $pack, array $registered, string $configFile = 'enchantments.json'): void
	{
		try {
			$config = json_decode((string) file_get_contents($pack->getDataFolder() . $configFile), true, 512, JSON_THROW_ON_ERROR);
		} catch (JsonException) {
			$config = [];
		}
		foreach ($registered as $key => $enchant) {
			if ($enchant instanceof SoulPowered) {
				$enchant->setSoulCost((int) ($config[$key]['soul_cost'] ?? 0));
			}
		}
	}
}
