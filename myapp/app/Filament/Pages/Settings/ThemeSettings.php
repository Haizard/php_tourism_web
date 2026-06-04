<?php

namespace App\Filament\Pages\Settings;

use App\Settings\ThemeSettings as ThemeSettingsModel;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\ColorPicker;
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

    protected function getFormSchema(): array
    {
        return [
            Card::make()->schema([
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
        ];
    }
}
