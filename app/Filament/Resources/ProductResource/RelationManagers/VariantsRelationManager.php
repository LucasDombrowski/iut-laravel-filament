<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Variant;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('color_id')
                   ->relationship("color", "label")
                   ->required()
                   ->createOptionForm(
                        [
                            Forms\Components\TextInput::make('label')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\ColorPicker::make('hex')
                                ->required()
                        ]
                    )
                    ->rules([
                        fn(Get $get) : Closure => function(string $attribute, $value,  Closure $fail) use ($get) {
                            $sizeId = $get('size_id');
                            $id = $get("id");
                            $colorId = $value;
                            if($sizeId){
                                $exists = Variant::where('size_id', $sizeId)->where('color_id', $colorId)->where("product_id",$this->ownerRecord->getKey())->where("id","!=",$id)->exists();
                                if($exists){
                                    $fail("This variant already exists.");
                                }
                            }
                        }
                    ])
                    ,
                Forms\Components\Select::make('size_id')
                     ->relationship("size", "label")
                     ->required()
                     ->createOptionForm(
                            [
                             Forms\Components\TextInput::make('label')
                                  ->required()
                                  ->maxLength(255),
                            ]
                    )
                    ->rules([
                        fn(Get $get): Closure => function(string $attribute, $value, Closure $fail) use ($get) {
                            $colorId = $get('color_id');
                            $id = $get("id");
                            $sizeId = $value;
                            if($colorId){
                                $exists = Variant::where('size_id', $sizeId)->where('color_id', $colorId)->where("product_id",$this->ownerRecord->getKey())->where("id","!=",$id)->exists();
                                if($exists){
                                    $fail("This variant already exists.");
                                }
                            }
                        }
                    ]),
                Forms\Components\TextInput::make('price')
                ->required()
                ->numeric()
                ->suffix("€")
                ->minValue(0),
                Forms\Components\TextInput::make('stock')
                ->required()
                ->numeric()
                ->minValue(0),
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
                    ->directory('variants'),
                Forms\Components\Repeater::make('images')
                ->relationship()
                ->columnSpanFull()
                ->orderColumn("order")
                ->schema(
                    [
                        Forms\Components\FileUpload::make('url')
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
                            ->required()
                            ->directory('variants'),
                        Forms\Components\TextInput::make('alt')
                            ->required()
                            ->maxLength(255),
                    ]
                )
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle("Edit Variant")
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Thumbnail'),
                Tables\Columns\ColorColumn::make('color.hex')
                    ->label('Color')
                    ->sortable(),
                Tables\Columns\TextColumn::make('size.label')
                    ->label('Size')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money("EUR")
                    ->sortable(),
                Tables\Columns\TextInputColumn::make('stock')
                    ->label('Stock')
                    ->sortable()
                    ->rules(["min:0","required"])
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
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
