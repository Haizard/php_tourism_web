<?php

namespace App\Filament\Pages\Settings;

use App\Settings\GeneralSettings as GeneralSettingsModel;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;

class GeneralSettings extends SettingsPage
{
    protected static ?string $title = 'General Settings';
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'General';
    protected static ?string $slug = 'settings/general';

    protected static function getSettingsClass(): string
    {
        return GeneralSettingsModel::class;
    }

    protected function getSettingsFormSchema(): array
    {
        return [
            Section::make('Site Identity')->schema([
                TextInput::make('siteName')
                    ->label('Site name')
                    ->required(),
                TextInput::make('tagline')
                    ->label('Tagline')
                    ->required(),
                TextInput::make('logo')
                    ->label('Logo URL')
                    ->url(),
                TextInput::make('favicon')
                    ->label('Favicon URL')
                    ->url(),
            ]),
            Section::make('Contact Details')->schema([
                TextInput::make('contactEmail')
                    ->label('Contact email')
                    ->email()
                    ->required(),
                TextInput::make('contactPhone')
                    ->label('Contact phone')
                    ->required(),
                TextInput::make('address')
                    ->label('Address')
                    ->required(),
            ]),
            Section::make('Social Links')->schema([
                Repeater::make('socialLinks')
                    ->label('Social links')
                    ->schema([
                        TextInput::make('label')
                            ->label('Label')
                            ->required(),
                        TextInput::make('url')
                            ->label('URL')
                            ->url()
                            ->required(),
                    ])
                    ->columnSpan('full')
                    ->addActionLabel('Add link'),
            ]),
        ];
    }
}
