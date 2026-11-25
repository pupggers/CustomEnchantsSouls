<?php

declare(strict_types=1);

namespace pup\souls;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pup\customenchants\api\CustomEnchantsAPI;
use pup\souls\command\SoulsCommand;

final class Main extends PluginBase
{
	public const array MESSAGES = [
		'souls_no_permission' => '{red}You do not have permission to do that.',
		'souls_usage' => '{red}Usage: /souls [player] | /souls <give|take> <player> <amount>',
		'souls_admin_usage' => '{red}Usage: /souls <give|take> <online player> <amount>',
		'souls_balance' => '{aqua}{target}{gray} has {white}{amount}{gray} souls.',
		'souls_updated' => '{green}{target} now has {amount} souls.',
		'souls_target_not_enough' => '{red}{target} only has {amount} souls.',
		'souls_not_enough' => '{red}Not enough souls ({amount} needed)',
		'souls_gained' => '{aqua}+{amount} souls {gray}({souls})',
	];

	protected function onEnable(): void
	{
		Souls::load($this);
		CustomEnchantsAPI::registerMessages($this, self::MESSAGES);
		CustomEnchantsAPI::registerPlaceholder('souls', function (?Player $player, ?string $params): string {
			$target = $params !== null ? $this->getServer()->getPlayerExact($params) : $player;
			return (string) ($target === null ? 0 : Souls::get($target));
		}, supportsParameters: true);

		$this->getServer()->getCommandMap()->register('souls', new SoulsCommand($this));
		$this->getScheduler()->scheduleRepeatingTask(new ClosureTask(static function (): void {
			Souls::save();
		}), 20 * 300);
	}

	protected function onDisable(): void
	{
		Souls::save();
	}
}
