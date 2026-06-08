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
                    ->description('Reviewer details')
                    ->schema([
                        Forms\Components\TextInput::make('author_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('author_title')
                            ->label('Author Title / Location')
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('author_image')
                            ->image()
                            ->directory('testimonials'),
                    ]),

                Forms\Components\Section::make('Review Content')
                    ->description('The review message and rating')
                    ->schema([
                        Forms\Components\TextInput::make('review_title')
                            ->label('Review Title')
                            ->maxLength(150)
                            ->placeholder('e.g. Amazing Safari Experience!'),
                        Forms\Components\Textarea::make('content')
                            ->required()
                            ->maxLength(2000),
                        Forms\Components\Select::make('rating')
                            ->options([
                                5 => '⭐⭐⭐⭐⭐ Excellent',
                                4 => '⭐⭐⭐⭐ Very Good',
                                3 => '⭐⭐⭐ Average',
                                2 => '⭐⭐ Poor',
                                1 => '⭐ Terrible',
                            ])
                            ->required()
                            ->default(5),
                        Forms\Components\Select::make('traveler_type')
                            ->label('Type of Traveler')
                            ->options([
                                'solo'     => '🧳 Solo Traveler',
                                'couple'   => '💑 Couple',
                                'family'   => '👨‍👩‍👧 Family',
                                'friends'  => '👫 Friends',
                                'business' => '💼 Business',
                            ])
                            ->nullable(),
                        Forms\Components\DatePicker::make('visit_date')
                            ->label('Visit Date')
                            ->nullable(),
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
                    ->description('Control review visibility and order')
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->label('Approve & Publish')
                            ->helperText('Only published reviews appear on the tour page.')
                            ->default(false),
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
                Tables\Columns\TextColumn::make('review_title')
                    ->label('Title')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\BadgeColumn::make('rating')
                    ->formatStateUsing(fn($state) => str_repeat('⭐', $state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('traveler_type')
                    ->label('Traveler')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'solo'     => '🧳 Solo',
                        'couple'   => '💑 Couple',
                        'family'   => '👨‍👩‍👧 Family',
                        'friends'  => '👫 Friends',
                        'business' => '💼 Business',
                        default    => '—',
                    }),
                Tables\Columns\BooleanColumn::make('is_published')
                    ->label('Approved')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tour.title')
                    ->searchable()
                    ->sortable()
                    ->limit(25),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Approval Status')
                    ->trueLabel('Approved')
                    ->falseLabel('Pending'),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTestimonial::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit'   => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
