<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Author Information')
                    ->description('Testimonial author details')
                    ->schema([
                        Forms\Components\TextInput::make('author_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('author_title')
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('author_image')
                            ->image()
                            ->directory('testimonials'),
                    ]),

                Forms\Components\Section::make('Testimonial Content')
                    ->description('The testimonial message and rating')
                    ->schema([
                        Forms\Components\Textarea::make('content')
                            ->required()
                            ->maxLength(2000),
                        Forms\Components\TextInput::make('rating')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(5)
                            ->step(0.5)
                            ->helperText('Rating from 1 to 5'),
                    ]),

                Forms\Components\Section::make('Tour Association')
                    ->description('Link to a specific tour (optional)')
                    ->schema([
                        Forms\Components\Select::make('tour_id')
                            ->relationship('tour', 'title')
                            ->searchable()
                            ->nullable(),
                    ]),

                Forms\Components\Section::make('Publishing')
                    ->description('Control testimonial visibility and order')
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->default(true),
                        Forms\Components\DatePicker::make('published_at'),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('author_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('rating')
                    ->formatStateUsing(fn($state) => "{$state} ⭐")
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_published')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tour.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestimonial::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
