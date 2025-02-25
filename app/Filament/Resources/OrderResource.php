<?php

namespace App\Filament\Resources;

use App\Enums\CountryCodeEnum;
use App\Enums\OrderStatusEnum;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\VariantsRelationManager;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->required()
                    ->relationship("user", "full_name")
                    ->searchable(),
                Forms\Components\Select::make('discount_id')
                    ->nullable()
                    ->relationship("discount", "code"),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options(
                        array_combine(
                            array_map(fn($status) => $status->value, OrderStatusEnum::cases()),
                            array_map(fn($status) => $status->label(), OrderStatusEnum::cases())
                        )
                    ),
                Forms\Components\TextInput::make('address_number')
                    ->required(),
                Forms\Components\TextInput::make('address_street')
                    ->required(),
                Forms\Components\TextInput::make('address_complement')
                ->nullable(),
                Forms\Components\TextInput::make('address_city')
                    ->required(),
                Forms\Components\TextInput::make('address_zip_code')
                    ->required(),
                Forms\Components\Select::make('address_country_code')
                    ->label("Address country")
                    ->options(array_combine(
                        array_map(fn($country) => $country->value, CountryCodeEnum::cases()),
                        array_map(fn($country) => $country->label(), CountryCodeEnum::cases())
                    ))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user')
                    ->formatStateUsing(fn(string $state, $record) => $record->user->first_name . ' ' . $record->user->last_name)
                    ->searchable(),
                Tables\Columns\TextColumn::make('discount.code')
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->searchable()
                    ->options(
                        array_combine(
                            array_map(fn($status) => $status->value, OrderStatusEnum::cases()),
                            array_map(fn($status) => $status->label(), OrderStatusEnum::cases())
                        )
                    )
                    ->sortable()
                    ->rules([
                        "required",
                        "in:" . implode(",", array_map(fn($status) => $status->value, OrderStatusEnum::cases()))
                    ])
                    ,
                Tables\Columns\TextColumn::make('address_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address_street')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address_complement')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address_city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address_zip_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address_country_code')
                    ->label("Address country")
                    ->formatStateUsing(fn(string $state, $record) => CountryCodeEnum::{$state}->label())
                    ->searchable(),
                Tables\Columns\TextColumn::make("total_price")
                    ->numeric()
                    ->sortable()
                    ->money("EUR")
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            VariantsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            //'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            //'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function canCreate() : bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }
}
