<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SectionBackgroundResource\Pages;
use App\Models\SectionBackground;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SectionBackgroundResource extends Resource
{
    protected static ?string $model = SectionBackground::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Appearance';

    protected static ?string $navigationLabel = 'Section Backgrounds';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Section')
                    ->schema([
                        Forms\Components\TextInput::make('section_name')
                            ->label('Section')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('section_key')
                            ->label('Key')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),

                Forms\Components\Section::make('Background')
                    ->schema([
                        Forms\Components\Select::make('bg_type')
                            ->label('Background Type')
                            ->options([
                                'none'  => 'None (default)',
                                'color' => 'Solid Color',
                                'image' => 'Background Image',
                            ])
                            ->default('none')
                            ->required()
                            ->live(),

                        Forms\Components\ColorPicker::make('bg_value')
                            ->label('Background Color')
                            ->visible(fn (Get $get) => $get('bg_type') === 'color'),

                        Forms\Components\FileUpload::make('bg_value')
                            ->label('Background Image')
                            ->image()
                            ->directory('section-backgrounds')
                            ->imageResizeMode('cover')
                            ->visible(fn (Get $get) => $get('bg_type') === 'image'),
                    ]),

                Forms\Components\Section::make('Overlay & Text')
                    ->schema([
                        Forms\Components\TextInput::make('overlay_opacity')
                            ->label('Overlay Opacity (0.0 – 1.0)')
                            ->numeric()
                            ->step(0.05)
                            ->minValue(0)
                            ->maxValue(1)
                            ->default(0)
                            ->helperText('Only applies when using a background image. 0 = no overlay, 0.5 = 50% dark overlay.')
                            ->visible(fn (Get $get) => $get('bg_type') === 'image'),

                        Forms\Components\Select::make('text_color')
                            ->label('Text Color on Section')
                            ->options([
                                'dark'  => 'Dark (default)',
                                'light' => 'Light (use on dark backgrounds)',
                            ])
                            ->default('dark')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id')
            ->columns([
                Tables\Columns\TextColumn::make('section_name')
                    ->label('Section')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('bg_type')
                    ->label('Background')
                    ->colors([
                        'secondary' => 'none',
                        'success'   => 'color',
                        'primary'   => 'image',
                    ]),

                Tables\Columns\ColorColumn::make('bg_value')
                    ->label('Color')
                    ->visible(fn () => false),

                Tables\Columns\TextColumn::make('bg_value')
                    ->label('Value')
                    ->limit(40)
                    ->placeholder('—'),

                Tables\Columns\BadgeColumn::make('text_color')
                    ->label('Text')
                    ->colors([
                        'secondary' => 'dark',
                        'warning'   => 'light',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSectionBackgrounds::route('/'),
            'edit'  => Pages\EditSectionBackground::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }
}
