<?php

namespace App\Filament\Resources;

use App\Enums\DiscountTypeEnum;
use App\Filament\Resources\DiscountResource\Pages;
use App\Filament\Resources\DiscountResource\RelationManagers;
use App\Filament\Resources\DiscountResource\RelationManagers\OrdersRelationManager;
use App\Models\Discount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->rules([
                        fn(Get $get) => function($attribute, $value, $fail) use ($get) {
                            //On regarde si le code existe déjà pour un discount encore en cours
                            $exists = Discount::where('code', $value)
                                ->where('end_at', '>', now())
                                ->where('id', '!=', $get('id'))
                                ->exists();
                            if ($exists) {
                                $fail("This code is already used for another available discount.");
                            }
                        }
                    ])
                    ,
                Forms\Components\DateTimePicker::make('start_at')
                    ->required()
                    ->minDate(now())
                    ,
                Forms\Components\DateTimePicker::make('end_at')
                    ->required()
                    ->minDate(now()),
                Forms\Components\TextInput::make('min_price')
                    ->required()
                    ->suffix('€')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options(array_combine(
                        array_map(fn($type) => $type->value, DiscountTypeEnum::cases()),
                        array_map(fn($type) => $type->label(), DiscountTypeEnum::cases())
                    )),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->numeric()
                    ->minValue(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('min_price')
                    ->numeric()
                    ->sortable()
                    ->money("EUR")
                    ,
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->numeric()
                    ->sortable(),
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            //'edit' => Pages\EditDiscount::route('/{record}/edit'),
        ];
    }
}
