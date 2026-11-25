<?php

declare(strict_types=1);

namespace pup\souls;

use JsonException;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pup\customenchants\api\CustomEnchantsAPI;

final class Souls
{
	private static string $path = '';

	/** @var array<string, int> */
	private static array $balances = [];

	private static bool $dirty = false;

	public static function load(PluginBase $plugin): void
	{
		self::$path = $plugin->getDataFolder() . 'souls.json';
		self::$balances = [];
		if (!is_file(self::$path)) {
			return;
		}
		try {
			$data = json_decode((string) file_get_contents(self::$path), true, 512, JSON_THROW_ON_ERROR);
		} catch (JsonException $e) {
			@copy(self::$path, self::$path . '.bak');
			$plugin->getLogger()->warning("souls.json is unreadable ({$e->getMessage()}); starting empty (backup: souls.json.bak).");
			return;
		}
		foreach (is_array($data) ? $data : [] as $id => $amount) {
			if (is_string($id) && is_int($amount)) {
				self::$balances[$id] = max(0, $amount);
			}
		}
	}

	public static function save(): void
	{
		if (!self::$dirty || self::$path === '') {
			return;
		}
		$tmp = self::$path . '.tmp';
		if (file_put_contents($tmp, json_encode(self::$balances, JSON_PRETTY_PRINT), LOCK_EX) !== false && rename($tmp, self::$path)) {
			self::$dirty = false;
		}
	}

	public static function get(Player $player): int
	{
		return self::$balances[self::id($player)] ?? 0;
	}

	public static function add(Player $player, int $amount): int
	{
		$id = self::id($player);
		self::$balances[$id] = max(0, (self::$balances[$id] ?? 0) + $amount);
		self::$dirty = true;
		return self::$balances[$id];
	}

	public static function take(Player $player, int $amount): bool
	{
		if ($amount <= 0) {
			return true;
		}
		if (self::get($player) < $amount) {
			return false;
		}
		self::add($player, -$amount);
		return true;
	}

	public static function pay(Player $player, int $amount): bool
	{
		if (self::take($player, $amount)) {
			return true;
		}
		CustomEnchantsAPI::sendPopup($player, 'souls_not_enough', ['amount' => $amount]);
		return false;
	}

	private static function id(Player $player): string
	{
		return $player->getUniqueId()->toString();
	}
}
