<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Booking Information')
                    ->description('Tour and booking details')
                    ->schema([
                        Forms\Components\Select::make('tour_id')
                            ->relationship('tour', 'title')
                            ->required()
                            ->searchable(),
                        Forms\Components\DatePicker::make('travel_date')
                            ->required(),
                        Forms\Components\TextInput::make('number_of_travelers')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                    ]),

                Forms\Components\Section::make('Guest Information')
                    ->description('Details of the guest making the booking')
                    ->schema([
                        Forms\Components\TextInput::make('guest_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('guest_email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('guest_phone')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                    ]),

                Forms\Components\Section::make('Pricing & Status')
                    ->description('Booking price and status')
                    ->schema([
                        Forms\Components\TextInput::make('total_price')
                            ->numeric()
                            ->required()
                            ->prefix('$'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('pending'),
                        Forms\Components\DateTimePicker::make('confirmed_at'),
                    ]),

                Forms\Components\Section::make('Additional Information')
                    ->description('Special requests and notes')
                    ->schema([
                        Forms\Components\Textarea::make('special_requests')
                            ->maxLength(1000),
                        Forms\Components\Textarea::make('notes')
                            ->maxLength(1000),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('guest_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tour.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('travel_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'pending',
                        'info' => 'confirmed',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status')
                    ->queries(
                        true: fn($query) => $query->where('status', 'confirmed'),
                        false: fn($query) => $query->where('status', '!=', 'confirmed'),
                    ),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooking::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
