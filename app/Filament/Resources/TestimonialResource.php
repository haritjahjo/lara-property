<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Testimonial;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Livewire\TemporaryUploadedFile;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TestimonialResource\Pages;
use App\Filament\Resources\TestimonialResource\RelationManagers;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('full_name')
                    ->maxLength(length: 255)
                    ->minLength(2)
                    ->required(),
                FileUpload::make('photo')
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return (string) str($file->getClientOriginalName())->prepend('testimonial-');
                    })
                    ->directory('testimonials')
                    ->required(),
                TextInput::make('company')
                    ->maxLength(length: 255)
                    ->minLength(2)
                    ->required(),
                TextInput::make('rating')
                    ->numeric()
                    ->integer()
                    ->mask(
                        fn (TextInput\Mask $mask) => $mask
                            ->range()
                            ->from(1) // Set the lower limit.
                            ->to(5) // Set the upper limit.
                            ->maxValue(5),
                    )
                    ->required()
                    ->helperText('Input rating between 1 to 5'),
                MarkdownEditor::make('testimonial')
                    ->disableAllToolbarButtons()
                    ->enableToolbarButtons([
                        'bold',
                        'bulletList',
                        'edit',
                        'italic',
                        'preview',
                        'strike',
                    ])->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(name: 'full_name')
                    ->searchable()
                    ->sortable(),
                ImageColumn::make(name: 'photo')->square(),
                TextColumn::make(name: 'company')
                    ->limit(25)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column contents exceeds the length limit.
                        return $state;
                    }),
                TextColumn::make(name: 'rating'),
                TextColumn::make('testimonial')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column contents exceeds the length limit.
                        return $state;
                    }),

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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
