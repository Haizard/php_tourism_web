<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NavItemResource\Pages;
use App\Models\Category;
use App\Models\NavItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NavItemResource extends Resource
{
    protected static ?string $model = NavItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationGroup = 'Navigation';

    protected static ?string $navigationLabel = 'Navbar Items';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Item Type')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Type')
                            ->options([
                                'manual'           => 'Manual (custom link)',
                                'category'         => 'Tour Category (auto-populates tours as dropdown)',
                                'destinations_hub' => 'Destinations Hub (auto-populates destinations as dropdown)',
                            ])
                            ->default('manual')
                            ->required()
                            ->live(),
                    ]),

                Forms\Components\Section::make('Item Details')
                    ->schema([
                        Forms\Components\TextInput::make('label')
                            ->label('Display Label')
                            ->placeholder('Leave blank to use category/type name')
                            ->maxLength(100),

                        Forms\Components\Select::make('reference_id')
                            ->label('Tour Category')
                            ->options(Category::where('is_active', true)->pluck('name', 'id'))
                            ->searchable()
                            ->visible(fn (Get $get) => $get('type') === 'category')
                            ->required(fn (Get $get) => $get('type') === 'category'),

                        Forms\Components\TextInput::make('url')
                            ->label('URL')
                            ->url()
                            ->placeholder('https://example.com or /page')
                            ->visible(fn (Get $get) => $get('type') === 'manual'),

                        Forms\Components\Select::make('parent_id')
                            ->label('Parent Item (optional)')
                            ->options(
                                NavItem::whereNull('parent_id')
                                    ->where('type', 'manual')
                                    ->pluck('label', 'id')
                            )
                            ->searchable()
                            ->nullable()
                            ->visible(fn (Get $get) => $get('type') === 'manual')
                            ->helperText('Only manual top-level items can be parents.'),
                    ]),

                Forms\Components\Section::make('Display Options')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first.'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                        Forms\Components\Toggle::make('open_in_new_tab')
                            ->label('Open in New Tab')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('display_label')
                    ->label('Label')
                    ->getStateUsing(fn (NavItem $record): string => $record->label ?: ($record->category?->name ?? ucfirst(str_replace('_', ' ', $record->type))))
                    ->searchable(query: fn ($query, $search) => $query->where('label', 'like', "%{$search}%"))
                    ->sortable(query: fn ($query, $dir) => $query->orderBy('label', $dir)),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'manual',
                        'success' => 'category',
                        'warning' => 'destinations_hub',
                    ]),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'manual'           => 'Manual',
                        'category'         => 'Category',
                        'destinations_hub' => 'Destinations Hub',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNavItems::route('/'),
            'create' => Pages\CreateNavItem::route('/create'),
            'edit'   => Pages\EditNavItem::route('/{record}/edit'),
        ];
    }
}
