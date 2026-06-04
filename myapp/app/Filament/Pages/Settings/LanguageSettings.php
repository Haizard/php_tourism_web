<?php

namespace App\Filament\Pages\Settings;

use App\Settings\LanguageSettings as LanguageSettingsModel;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

class LanguageSettings extends SettingsPage
{
    protected static ?string $title = 'Language Settings';
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationLabel = 'Languages';
    protected static ?string $slug = 'settings/language';

    protected static function getSettingsClass(): string
    {
        return LanguageSettingsModel::class;
    }

    protected function getSettingsFormSchema(): array
    {
        return [
            Section::make('Locale Configuration')->schema([
                Select::make('defaultLocale')
                    ->label('Default locale')
                    ->options($this->getLocaleOptions())
                    ->required(),
                CheckboxList::make('enabledLocales')
                    ->label('Enabled locales')
                    ->options($this->getLocaleOptions())
                    ->columns(2)
                    ->required(),
            ]),
        ];
    }

    protected function getLocaleOptions(): array
    {
        return collect(config('tourism.supported_locales', []))
            ->mapWithKeys(fn ($locale, $key) => [$key => $locale['name']])
            ->toArray();
    }
}
