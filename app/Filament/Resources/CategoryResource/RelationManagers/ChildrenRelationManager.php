<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ChildrenRelationManager extends RelationManager
{
    protected static string $relationship = 'children';
    protected static ?string $inverseRelationship = 'parent';

    public function form(Form $form): Form
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
                    ->columnSpanFull()
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail'),
                Tables\Columns\TextInputColumn::make('name')
                ->rules(['required', 'max:255']),
                Tables\Columns\TextInputColumn::make('slug')
                ->rules(['required', 'max:255']),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AssociateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DissociateAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
