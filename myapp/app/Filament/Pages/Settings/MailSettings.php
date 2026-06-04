<?php

namespace App\Filament\Pages\Settings;

use App\Settings\MailSettings as MailSettingsModel;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class MailSettings extends SettingsPage
{
    protected static ?string $title = 'Mail Settings';
    protected static ?string $navigationIcon = 'heroicon-o-inbox';
    protected static ?string $navigationLabel = 'Mail';
    protected static ?string $slug = 'settings/mail';

    protected static function getSettingsClass(): string
    {
        return MailSettingsModel::class;
    }

    protected function getSettingsFormSchema(): array
    {
        return [
            Section::make('Email Configuration')->schema([
                TextInput::make('adminNotificationEmail')
                    ->label('Support email')
                    ->email()
                    ->required(),
                TextInput::make('fromName')
                    ->label('Notification sender name')
                    ->required(),
                TextInput::make('fromAddress')
                    ->label('Notification sender email')
                    ->email()
                    ->required(),
                TextInput::make('replyTo')
                    ->label('Reply-to address')
                    ->email(),
                Toggle::make('mailEnabled')
                    ->label('Mail enabled')
                    ->helperText('Enable sending transactional and notification email from the app.'),
            ]),
        ];
    }
}
