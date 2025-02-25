<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\AddressesRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\OrdersRelationManager;
use App\Models\Role;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->required(),
                Forms\Components\TextInput::make('last_name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),
                Forms\Components\FileUpload::make('profile_photo_path')
                    ->image()
                    ->directory("users")
                    ->avatar()
                    ->getUploadedFileUsing(static function (BaseFileUpload $component, string $file, string|array|null $storedFileNames): ?array {

                        return [
                            'name' => basename($file),
                            'size' => 0,
                            'type' => null,
                            'url' => $file,
                        ];
                    })
                    ->afterStateHydrated(static function (BaseFileUpload $component, string|array|null $state) {
                        if (blank($state)) {
                            $component->state([]);

                            return;
                        }
                        $component->state([((string) Str::uuid()) => $state]);
                    })
                    ->previewable()
                    ->label("Photo")
                    ,
                Forms\Components\Select::make('role_id')
                    ->relationship('role', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo_path')
                    ->label('Photo'),
                Tables\Columns\TextInputColumn::make('first_name')
                    ->searchable()
                    ->rules(["required", "max:255"]),
                Tables\Columns\TextInputColumn::make('last_name')
                    ->searchable()
                    ->rules(["required", "max:255"]),
                Tables\Columns\TextInputColumn::make('email')
                    ->searchable()
                    ->rules(["required", "email", "max:255", "unique:users,email"]),
                Tables\Columns\SelectColumn::make('role_id')
                    ->label("Role")
                    ->options(
                        //Tous les roles transformés en tableau associatif avec pour association clé-valeur l'id du role et son noù.
                        Role::all()->pluck('name', 'id')->toArray()
                    )
                    ->rules(["required"]),
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
                Tables\Actions\EditAction::make(),
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
            AddressesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
