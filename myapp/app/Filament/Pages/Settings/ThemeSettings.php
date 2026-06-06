<?php

namespace App\Filament\Pages\Settings;

use App\Settings\ThemeSettings as ThemeSettingsModel;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class ThemeSettings extends SettingsPage
{
    protected static ?string $title = 'Theme Settings';
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Theme';
    protected static ?string $slug = 'settings/theme';

    protected static function getSettingsClass(): string
    {
        return ThemeSettingsModel::class;
    }

    protected function getSettingsFormSchema(): array
    {
        return [
            Section::make('Appearance')->schema([
                ColorPicker::make('primaryColor')
                    ->label('Primary color')
                    ->required(),
                ColorPicker::make('accentColor')
                    ->label('Accent color')
                    ->required(),
                ColorPicker::make('backgroundColor')
                    ->label('Background color')
                    ->required(),
                TextInput::make('fontFamily')
                    ->label('Font family')
                    ->helperText('Comma-separated font stack for the public site.')
                    ->required(),
                TextInput::make('heroOverlayOpacity')
                    ->label('Hero overlay opacity')
                    ->numeric()
                    ->step(0.05)
                    ->minValue(0)
                    ->maxValue(1)
                    ->required(),
            ]),

            Section::make('Custom CSS')
                ->description('Inject additional CSS that applies to every page on the public site. Changes here override the default styles.')
                ->schema([
                    Textarea::make('customCss')
                        ->label('Custom CSS')
                        ->rows(12)
                        ->placeholder("/* Example: change the button radius */\n.navbar-pill { border-radius: 8px; }\n\n/* Change hero font size */\n.page-hero h1 { font-size: 4rem; }")
                        ->helperText('Plain CSS only — no <style> tags needed.'),
                ]),
        ];
    }
}
