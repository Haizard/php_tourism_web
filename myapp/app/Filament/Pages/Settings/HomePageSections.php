<?php

namespace App\Filament\Pages\Settings;

use App\Settings\HomePageSettings;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;

class HomePageSections extends SettingsPage
{
    protected static ?string $title           = 'Homepage Sections';
    protected static ?string $navigationIcon  = 'heroicon-o-view-columns';
    protected static ?string $navigationLabel = 'Homepage Sections';
    protected static ?string $slug            = 'settings/homepage-sections';
    protected static ?int    $navigationSort  = 2;

    protected static function getSettingsClass(): string
    {
        return HomePageSettings::class;
    }

    protected function getSettingsFormSchema(): array
    {
        return [
            Section::make('Section Order & Visibility')
                ->description('Drag the ⠿ handle to reorder sections. Toggle the switch to show or hide each section on the homepage. Changes take effect immediately after saving.')
                ->schema([
                    Repeater::make('sections')
                        ->label('')
                        ->schema([
                            Hidden::make('key'),
                            Hidden::make('label'),
                            Grid::make([
                                'default' => 2,
                            ])->schema([
                                Placeholder::make('section_name')
                                    ->label('')
                                    ->content(fn (Get $get): string =>
                                        $get('label') ?: ucwords(str_replace('_', ' ', $get('key') ?: 'Section'))
                                    ),
                                Toggle::make('is_active')
                                    ->label('Visible on homepage')
                                    ->default(true)
                                    ->inline(true),
                            ]),
                        ])
                        ->reorderable()
                        ->reorderableWithDragAndDrop()
                        ->addable(false)
                        ->deletable(false)
                        ->itemLabel(fn (array $state): string =>
                            (($state['is_active'] ?? true) ? '👁  ' : '🚫  ') .
                            ($state['label'] ?? ucwords(str_replace('_', ' ', $state['key'] ?? 'Section')))
                        )
                        ->collapsible()
                        ->collapsed(),
                ]),
        ];
    }
}
