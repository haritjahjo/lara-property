<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Property;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Livewire\TemporaryUploadedFile;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\PropertyResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PropertyResource\RelationManagers;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(columns: 12)
            ->schema([
                Select::make('agent_id')
                    ->columnSpan(span: 6)
                    ->relationship('agent', 'full_name'),
                TextInput::make('address')
                    ->columnSpan(span: 6)
                    ->maxLength(length: 255)
                    ->minLength(2)
                    ->required(),
                TextInput::make('country')
                    ->columnSpan(span: 6)
                    ->maxLength(length: 255)
                    ->minLength(2)
                    ->required(),
                MarkdownEditor::make('description')
                    ->columnSpan(span: 6)
                    ->disableAllToolbarButtons()
                    ->enableToolbarButtons([
                        'bold',
                        'bulletList',
                        'edit',
                        'italic',
                        'preview',
                        'strike',
                    ])->required(),
                TextInput::make('price')
                    ->columnSpan(span: 4)
                    ->numeric()
                    ->required(),
                TextInput::make('beds')
                    ->columnSpan(span: 4)
                    ->numeric()
                    ->integer()
                    ->minValue(1)
                    ->maxValue(10)
                    ->helperText('Input beds between 1 to 10')
                    ->required(),
                TextInput::make('baths')
                    ->columnSpan(span: 4)
                    ->numeric()
                    ->integer()
                    ->minValue(1)
                    ->maxValue(10)
                    ->helperText('Input baths between 1 to 10')
                    ->required(),
                Toggle::make('is_popular')->inline()
                    ->columnSpan(span: 6),
                Toggle::make('is_featured')->inline()
                    ->columnSpan(span: 6),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(name: 'agent.full_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make(name: 'price')
                    ->money('usd'),
                TextColumn::make(name: 'address')
                    ->limit(25)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column contents exceeds the length limit.
                        return $state;
                    }),
                TextColumn::make(name: 'country'),
                TextColumn::make(name: 'beds'),
                TextColumn::make(name: 'baths'),
                TextColumn::make(name: 'description')
                    ->limit(25)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column contents exceeds the length limit.
                        return $state;
                    }),
                IconColumn::make('is_popular')
                    ->boolean()
                    ->trueIcon('heroicon-o-badge-check')
                    ->falseIcon('heroicon-o-x-circle'),
                IconColumn::make('is_featured')
                    ->boolean()
                    ->trueIcon('heroicon-o-badge-check')
                    ->falseIcon('heroicon-o-x-circle'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
