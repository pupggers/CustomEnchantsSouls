# Changelog

All notable changes to CustomEnchantsSouls are documented here.

## [1.0.1] - Current repository version

### Features

- Added a shared soul balance system for CustomEnchants packs.
- Added UUID-based player balance storage.
- Added soul balance retrieval, addition, and deduction methods.
- Added a payment method that reports insufficient balances through CustomEnchants.
- Added periodic balance saving every five minutes.
- Added a save operation when the plugin is disabled.
- Added JSON storage recovery that attempts to back up unreadable data before starting with empty balances.

### Commands

- Added `/souls` to check the sender's balance.
- Added `/souls <player>` to check an online player's balance.
- Added `/souls give <player> <amount>` for administrators.
- Added `/souls take <player> <amount>` for administrators.
- Added `souls.command` and `souls.command.admin` permissions.

### Developer API

- Added the `SoulPowered` interface for enchantments with soul costs.
- Added `SoulCostTrait` for storing and retrieving soul costs.
- Added a protected payment helper for soul-powered enchantments.
- Added `SoulCosts::apply()` to load enchantment costs from an owning plugin's `enchantments.json`.
- Added support for a custom enchantment configuration filename.

### Integration

- Added the required `CustomEnchants` plugin dependency.
- Registered the `souls` placeholder.
- Added support for `{souls}` and `{souls:PlayerName}`.
- Registered configurable soul-related messages through CustomEnchants.

### Configuration

- Added configurable messages for permission errors, command usage, balance checks, balance updates, insufficient balances, and soul gains.
- Added support for disabling individual messages with an empty string.

### Build and release

- Added a GitHub Actions release workflow using AxolotlBucketLabs DevTools.
- Added PHP syntax checking and automated PHAR building through the included publishing workflow configuration.
- Added a BedrockNexus publishing workflow template.

---
