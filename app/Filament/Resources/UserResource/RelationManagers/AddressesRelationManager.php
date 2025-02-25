<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enums\CountryCodeEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->required(),
                Forms\Components\TextInput::make('street')
                    ->required(),
                Forms\Components\TextInput::make('complement')
                    ->nullable(),
                Forms\Components\TextInput::make('city')
                    ->required(),
                Forms\Components\TextInput::make('zip_code')
                    ->required(),
                Forms\Components\Select::make('country_code')
                    ->searchable()
                    ->options(array_combine(
                        array_map(fn($country) => $country->value, CountryCodeEnum::cases()),
                        array_map(fn($country) => $country->label(), CountryCodeEnum::cases())
                    ))
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('street')
            ->columns([
                Tables\Columns\TextInputColumn::make('number')
                    ->searchable()
                    ->rules(["required"]),
                Tables\Columns\TextInputColumn::make('street')
                    ->searchable()
                    ->rules(["required"]),
                Tables\Columns\TextInputColumn::make('complement')
                    ->searchable()
                    ->rules(["nullable"]),
                Tables\Columns\TextInputColumn::make('city')
                    ->searchable()
                    ->rules(["required"]),
                Tables\Columns\TextInputColumn::make('zip_code')
                    ->searchable()
                    ->rules(["required"]),
                Tables\Columns\SelectColumn::make("country_code")
                    ->searchable()
                    ->label("Country")
                    ->rules(["required","in:".implode(",",array_map(fn($country) => $country->value, CountryCodeEnum::cases()))])
                    ->options(array_combine(
                        array_map(fn($country) => $country->value, CountryCodeEnum::cases()),
                        array_map(fn($country) => $country->label(), CountryCodeEnum::cases())
                    )),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
