<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Filament\Resources\CategoryResource\RelationManagers\ChildrenRelationManager;
use App\Filament\Resources\CategoryResource\RelationManagers\ProductsRelationManager;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->unique()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('thumbnail')
                    ->image()
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
                    ->required()
                    ->directory('categories'),
                Forms\Components\Select::make("parent_id")
                ->searchable()
                ->relationship("parent", "name")
                ->nullable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail'),
                Tables\Columns\TextInputColumn::make('name')
                    ->searchable()
                    ->rules([
                        "required"
                    ]),
                Tables\Columns\TextInputColumn::make('slug')
                    ->searchable()
                    ->rules([
                        "required",
                        "unique:categories,slug"
                    ]),
                Tables\Columns\TextColumn::make('parent.name')
                    ->searchable()
                    ->label('Parent'),
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
            ProductsRelationManager::class,
            ChildrenRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
