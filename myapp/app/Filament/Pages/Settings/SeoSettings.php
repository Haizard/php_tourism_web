<?php

namespace App\Filament\Pages\Settings;

use App\Settings\SeoSettings as SeoSettingsModel;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class SeoSettings extends SettingsPage
{
    protected static ?string $title = 'SEO Settings';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'SEO';
    protected static ?string $slug = 'settings/seo';

    protected static function getSettingsClass(): string
    {
        return SeoSettingsModel::class;
    }

    protected function getSettingsFormSchema(): array
    {
        return [
            Section::make('Search Engine Optimization')->schema([
                TextInput::make('defaultMetaTitle')
                    ->label('Default meta title')
                    ->required(),
                Textarea::make('defaultMetaDescription')
                    ->label('Default meta description')
                    ->rows(3)
                    ->required(),
                TextInput::make('ogImage')
                    ->label('Open Graph image URL')
                    ->url(),
                TextInput::make('twitterHandle')
                    ->label('Twitter handle')
                    ->placeholder('@yourhandle'),
            ]),
        ];
    }
}
