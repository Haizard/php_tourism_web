<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NavbarItemResource\Pages;
use App\Models\Category;
use App\Models\Destination;
use App\Models\NavbarItem;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NavbarItemResource extends Resource
{
    protected static ?string $model = NavbarItem::class;
    protected static ?string $navigationIcon = 'heroicon-o-bars-3';
    protected static ?string $navigationLabel = 'Navbar Items';
    protected static ?string $navigationGroup = 'Site Structure';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Navbar Item')->schema([
                Grid::make(2)->schema([
                    Select::make('type')
                        ->label('Item type')
                        ->options([
                            'custom'      => 'Custom link',
                            'category'    => 'Tour Category (auto dropdown)',
                            'destination' => 'Destination (auto dropdown)',
                        ])
                        ->required()
                        ->live()
                        ->default('custom'),

                    TextInput::make('sort_order')
                        ->label('Sort order')
                        ->numeric()
                        ->default(0)
                        ->required(),
                ]),

                TextInput::make('label')
                    ->label('Label')
                    ->helperText('Leave blank to use the category/destination name automatically.')
                    ->maxLength(100),

                TextInput::make('url')
                    ->label('URL')
                    ->url()
                    ->helperText('Required for custom links. Leave blank for category/destination items.')
                    ->visible(fn (Get $get) => $get('type') === 'custom'),

                Select::make('reference_id')
                    ->label('Category')
                    ->options(Category::where('is_active', true)->pluck('name', 'id'))
                    ->searchable()
                    ->visible(fn (Get $get) => $get('type') === 'category')
                    ->required(fn (Get $get) => $get('type') === 'category'),

                Select::make('reference_id')
                    ->label('Destination')
                    ->options(Destination::where('is_published', true)->pluck('name', 'id'))
                    ->searchable()
                    ->visible(fn (Get $get) => $get('type') === 'destination')
                    ->required(fn (Get $get) => $get('type') === 'destination'),

                Grid::make(2)->schema([
                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                    Toggle::make('open_in_new_tab')
                        ->label('Open in new tab')
                        ->default(false),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->width(50),
                TextColumn::make('resolved_label')
                    ->label('Label')
                    ->searchable(query: fn ($query, $search) => $query->where('label', 'like', "%{$search}%")),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'custom'      => 'gray',
                        'category'    => 'success',
                        'destination' => 'info',
                        default       => 'gray',
                    }),
                TextColumn::make('url')
                    ->label('URL')
                    ->limit(40)
                    ->placeholder('— auto —'),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNavbarItems::route('/'),
            'create' => Pages\CreateNavbarItem::route('/create'),
            'edit'   => Pages\EditNavbarItem::route('/{record}/edit'),
        ];
    }
}
