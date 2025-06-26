<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'المنتجات والمخزون';

    protected static ?string $navigationLabel = 'العلامات التجارية';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات العلامة التجارية')
                    ->schema([
                        TextInput::make('name')
                            ->label('اسم العلامة التجارية')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('وصف العلامة التجارية')
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        FileUpload::make('logo')
                            ->label('شعار العلامة التجارية')
                            ->image()
                            ->imageEditor()
                            ->directory('brands')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('الشعار')
                    ->circular(),

                TextColumn::make('name')
                    ->label('اسم العلامة التجارية')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('الوصف')
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('products_count')
                    ->label('عدد المنتجات')
                    ->formatStateUsing(fn(Brand $record) => $record->products()->count())
                    ->sortable(),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
