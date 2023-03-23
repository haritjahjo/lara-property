<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Agent;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Layout;
use Livewire\TemporaryUploadedFile;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\AgentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AgentResource\RelationManagers;

class AgentResource extends Resource
{
    protected static ?string $model = Agent::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('full_name')
                    ->maxLength(length: 255)
                    ->minLength(2)
                    ->required(),
                Select::make('title')
                    ->options([
                        'Real Estate Agent' => 'Real Estate Agent',
                        'Developer' => 'Developer',
                        'Marketing' => 'Marketing',
                    ])
                    ->default('Real Estate Agent')
                    ->disablePlaceholderSelection()
                    ->required(),
                MarkdownEditor::make('content')
                    ->disableAllToolbarButtons()
                    ->enableToolbarButtons([
                        'bold',
                        'bulletList',
                        'edit',
                        'italic',
                        'preview',
                        'strike',
                    ])->required(),
                FileUpload::make('photo')
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return (string) str($file->getClientOriginalName())->prepend('agents-');
                    })
                    ->directory('agents')->required(),
                TextInput::make(name: 'twitter')
                    ->url()
                    ->prefix('https://')
                    ->suffixIcon('heroicon-s-external-link'),
                TextInput::make(name: 'facebook')
                    ->url()
                    ->prefix('https://')
                    ->suffixIcon('heroicon-s-external-link'),
                TextInput::make(name: 'linkedin')
                    ->url()
                    ->prefix('https://')
                    ->suffixIcon('heroicon-s-external-link'),
                TextInput::make(name: 'instagram')
                    ->url()
                    ->prefix('https://')
                    ->suffixIcon('heroicon-s-external-link'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(name: 'full_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make(name: 'title'),
                TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column contents exceeds the length limit.
                        return $state;
                    }),
                ImageColumn::make(name: 'photo')->square(),
                TextColumn::make(name: 'twitter'),
                TextColumn::make(name: 'facebook'),
                TextColumn::make(name: 'linkedin'),
                TextColumn::make(name: 'instagram'),
            ])
            ->filters(
                [
                    //
                ]
            )
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
            'index' => Pages\ListAgents::route('/'),
            'create' => Pages\CreateAgent::route('/create'),
            'edit' => Pages\EditAgent::route('/{record}/edit'),
        ];
    }
}
