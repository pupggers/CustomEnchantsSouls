# CustomEnchantsSouls

**A shared soul currency system for CustomEnchants.**

CustomEnchantsSouls provides a persistent soul balance for players and an API that allows CustomEnchants packs to make enchantments consume souls.

It is designed to be used alongside [CustomEnchants](https://github.com/pupggers/CustomEnchants), giving multiple enchantment packs access to the same player soul balances.

## Features

- **Shared soul balances** — all supported enchantment packs use the same player balance.
- **Persistent storage** — balances are stored in `souls.json`, keyed by player UUID.
- **Soul management commands** — players can check their balance, while administrators can give or take souls.
- **Enchant integration API** — enchantments can implement `SoulPowered` and use `SoulCostTrait`.
- **Configurable enchantment costs** — individual enchantment costs can be read from an owning pack's `enchantments.json`.
- **Placeholder support** — display a player's soul balance through CustomEnchants' PlaceholderAPI integration.
- **Configurable messages** — customize command and soul-related messages.

## Requirements

| Requirement | Details |
|---|---|
| Server software | Axolotl-MP |
| API version | 5.0.0 |
| PHP | 8.4 |
| Dependency | [CustomEnchants](https://github.com/pupggers/CustomEnchants) |

## Installation

CustomEnchantsSouls is an addon for [CustomEnchants](https://github.com/pupggers/CustomEnchants). It extends CustomEnchants with a shared soul currency system used by compatible enchantment packs.

1. Install [CustomEnchants](https://github.com/pupggers/CustomEnchants) on your Axolotl-MP server.
2. Download the latest `CustomEnchantsSouls.phar` from the [Releases](https://github.com/pupggers/CustomEnchantsSouls/releases) page.
3. Place `CustomEnchantsSouls.phar` in your server's `plugins/` directory alongside CustomEnchants. (Or install it through `/ce addon install pupggers/CustomEnchantsSouls`)
4. Restart your server.

CustomEnchantsSouls requires CustomEnchants to be installed and enabled. It will not work independently.

On its first load, the plugin initializes its soul storage. Its configuration file is located at:

```text
plugin_data/
└── CustomEnchantsSouls/
    ├── config.yml
    └── souls.json
```

The `souls.json` file is created when balances are saved.

## Commands

### `/souls`

Displays your current soul balance.

**Permission:** `souls.command`  
**Default:** Everyone

### `/souls <player>`

Displays the balance of an online player.

**Permission:** `souls.command`  
**Default:** Everyone

### `/souls give <player> <amount>`

Gives souls to an online player.

**Permission:** `souls.command.admin`  
**Default:** Operators

### `/souls take <player> <amount>`

Takes souls from an online player. The command will not remove more souls than the target currently has.

**Permission:** `souls.command.admin`  
**Default:** Operators

The target is resolved using PocketMine-MP's player-prefix lookup, so the player must be online.

## Permissions

| Permission | Default | Description |
|---|---|---|
| `souls.command` | `true` | Check your own or another online player's soul balance. |
| `souls.command.admin` | `op` | Give or take souls from online players. |

## Placeholders

CustomEnchantsSouls registers the `souls` placeholder through CustomEnchants.

| Placeholder | Description |
|---|---|
| `{souls}` | The viewing player's soul balance. |
| `{souls:PlayerName}` | The specified online player's soul balance. |

The parameterized form resolves an online player by their exact name. If the player cannot be resolved, the placeholder returns `0`.

These placeholders can be used in message strings processed through CustomEnchants' PlaceholderAPI integration.

## Configuration

The default `resources/config.yml` contains the plugin's message configuration:

```yaml
messages:
  souls_no_permission: "{red}You do not have permission to do that."
  souls_usage: "{red}Usage: /souls [player] | /souls <give|take> <player> <amount>"
  souls_admin_usage: "{red}Usage: /souls <give|take> <online player> <amount>"
  souls_balance: "{aqua}{target}{gray} has {white}{amount}{gray} souls."
  souls_updated: "{green}{target} now has {amount} souls."
  souls_target_not_enough: "{red}{target} only has {amount} souls."
  souls_not_enough: "{red}Not enough souls ({amount} needed)"
  souls_gained: "{aqua}+{amount} souls {gray}({souls})"
```

Messages support CustomEnchants' PlaceholderAPI formatting, including color
