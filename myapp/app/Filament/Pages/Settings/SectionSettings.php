<?php

namespace App\Filament\Pages\Settings;

use App\Settings\SectionSettings as SectionSettingsModel;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

class SectionSettings extends SettingsPage
{
    protected static ?string $title = 'Section Backgrounds';
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Section Backgrounds';
    protected static ?string $slug = 'settings/sections';

    protected static function getSettingsClass(): string
    {
        return SectionSettingsModel::class;
    }

    protected function getSettingsFormSchema(): array
    {
        $sections = [
            'hero'           => 'Hero Banner',
            'featured_tours' => 'Featured Tours',
            'destinations'   => 'Destinations',
            'about'          => 'About / Features',
            'statistics'     => 'Statistics',
            'testimonials'   => 'Testimonials',
            'gallery'        => 'Gallery',
            'blogs'          => 'Blog / Journal',
            'faq'            => 'FAQ',
            'newsletter'     => 'Newsletter',
            'contact'        => 'Contact',
        ];

        $schema = [];

        foreach ($sections as $key => $label) {
            $schema[] = Section::make($label)->schema([
                Grid::make(3)->schema([
                    Select::make("{$key}_bg_type")
                        ->label('Background type')
                        ->options([
                            'none'  => 'None (transparent)',
                            'color' => 'Solid color',
                            'image' => 'Background image',
                        ])
                        ->live()
                        ->required(),
                    ColorPicker::make("{$key}_bg_color")
                        ->label('Background color')
                        ->visible(fn ($get) => $get("{$key}_bg_type") === 'color'),
                    FileUpload::make("{$key}_bg_image")
                        ->label('Background image')
                        ->image()
                        ->disk('public')
                        ->directory('section-backgrounds')
                        ->imageResizeMode('cover')
                        ->visible(fn ($get) => $get("{$key}_bg_type") === 'image')
                        ->columnSpan(2),
                ]),
            ])->collapsible()->collapsed();
        }

        return $schema;
    }
}
