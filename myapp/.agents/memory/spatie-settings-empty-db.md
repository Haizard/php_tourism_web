---
name: Spatie laravel-settings empty-DB save bug
description: Why settings save() throws MissingSettings when the DB settings table is empty, and how to fix it.
---

## The Rule

Always seed the `settings` table with initial DB rows (via `SettingsSeeder`) before any settings save is attempted. If the table is empty, Spatie will throw `MissingSettings` for ALL properties even though the PHP class has defaults.

## Why

Spatie's `SettingsMapper::fillMissingSettingsWithDefaultValues()` marks every property that falls back to a PHP class default as a "default-value-loaded" property (stored in `SettingsConfig::$defaultValueLoadedProperties`). During save (`SettingsMapper::save()`), `ensureNoMissingSettings()` appends ALL `defaultValueLoadedProperties` to the "missing" list (line 144 of SettingsMapper.php). So an empty DB → every property is default-loaded → every property is "missing" during save → exception.

## How to Apply

- Run `php artisan db:seed --class=SettingsSeeder` after migrations on any fresh environment.
- `SettingsSeeder` uses `DB::table('settings')->updateOrInsert(...)` with raw JSON payloads — it bypasses the Spatie load/save cycle entirely.
- Once rows exist in the DB, loading reads from DB (not from PHP defaults), `defaultValueLoadedProperties` stays empty, and saving works normally.
- The Filament settings page form uses `public array $data = []` + `->statePath('data')` + reads `$this->data` via `$this->form->getState()`. This is the correct Filament v3 pattern.
