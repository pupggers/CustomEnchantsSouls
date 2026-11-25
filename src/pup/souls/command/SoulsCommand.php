<?php

declare(strict_types=1);

namespace pup\souls\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pup\customenchants\api\CustomEnchantsAPI;
use pup\souls\Souls;

final class SoulsCommand extends Command implements PluginOwned
{
	public function __construct(private readonly Plugin $plugin)
	{
		parent::__construct('souls', 'Check or manage souls', '/souls [player] | /souls <give|take> <player> <amount>');
		$this->setPermission('souls.command');
	}

	public function getOwningPlugin(): Plugin
	{
		return $this->plugin;
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args): void
	{
		$action = strtolower($args[0] ?? '');

		if ($action === 'give' || $action === 'take') {
			if (!$sender->hasPermission('souls.command.admin')) {
				CustomEnchantsAPI::sendMessage($sender, 'souls_no_permission');
				return;
			}
			$target = $this->plugin->getServer()->getPlayerByPrefix($args[1] ?? '');
			$amount = (int) ($args[2] ?? 0);
			if ($target === null || $amount <= 0) {
				CustomEnchantsAPI::sendMessage($sender, 'souls_admin_usage');
				return;
			}
			if ($action === 'give') {
				Souls::add($target, $amount);
			} elseif (!Souls::take($target, $amount)) {
				CustomEnchantsAPI::sendMessage($sender, 'souls_target_not_enough', ['target' => $target->getName(), 'amount' => Souls::get($target)]);
				return;
			}
			CustomEnchantsAPI::sendMessage($sender, 'souls_updated', ['target' => $target->getName(), 'amount' => Souls::get($target)]);
			return;
		}

		$target = $action !== '' ? $this->plugin->getServer()->getPlayerByPrefix($args[0]) : ($sender instanceof Player ? $sender : null);
		if ($target === null) {
			CustomEnchantsAPI::sendMessage($sender, 'souls_usage');
			return;
		}
		CustomEnchantsAPI::sendMessage($sender, 'souls_balance', ['target' => $target->getName(), 'amount' => Souls::get($target)]);
	}
}
