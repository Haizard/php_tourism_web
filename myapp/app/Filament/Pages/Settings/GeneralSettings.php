<?php

namespace App\Filament\Pages\Settings;

use App\Settings\GeneralSettings as GeneralSettingsModel;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
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
            Section::make('Social Media Links')
                ->description('Choose a platform — the real icon will appear automatically in the footer.')
                ->schema([
                    Repeater::make('socialLinks')
                        ->label('Social links')
                        ->schema([
                            Select::make('platform')
                                ->label('Platform')
                                ->required()
                                ->options([
                                    'facebook'  => '📘 Facebook',
                                    'instagram' => '📸 Instagram',
                                    'twitter'   => '🐦 Twitter / X',
                                    'youtube'   => '▶️ YouTube',
                                    'whatsapp'  => '💬 WhatsApp',
                                    'linkedin'  => '💼 LinkedIn',
                                    'tiktok'    => '🎵 TikTok',
                                    'pinterest' => '📌 Pinterest',
                                    'snapchat'  => '👻 Snapchat',
                                ])
                                ->native(false)
                                ->searchable()
                                ->columnSpan(1),
                            TextInput::make('url')
                                ->label('Profile URL')
                                ->url()
                                ->required()
                                ->placeholder('https://facebook.com/yourpage')
                                ->columnSpan(1),
                        ])
                        ->columns(2)
                        ->columnSpan('full')
                        ->addActionLabel('Add social link')
                        ->reorderable()
                        ->collapsible(),
                ]),
        ];
    }
}
