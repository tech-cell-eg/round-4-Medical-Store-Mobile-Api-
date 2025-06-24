<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationGroup = 'المنتجات والمخزون';
    
    protected static ?string $navigationLabel = 'المنتجات';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات المنتج الأساسية')
                    ->schema([
                        TextInput::make('name')
                            ->label('اسم المنتج')
                            ->required()
                            ->maxLength(255),
                        
                        Textarea::make('description')
                            ->label('وصف المنتج')
                            ->required()
                            ->columnSpanFull(),
                        
                        Select::make('category_id')
                            ->label('الفئة')
                            ->relationship('category', 'name')
                            ->required(),
                        
                        Select::make('brand_id')
                            ->label('العلامة التجارية')
                            ->relationship('brand', 'name')
                            ->required(),
                        
                        Select::make('unit_id')
                            ->label('وحدة القياس')
                            ->relationship('unit', 'name')
                            ->required(),
                    ])->columns(2),
                    
                Forms\Components\Section::make('معلومات إضافية')
                    ->schema([
                        DatePicker::make('production_date')
                            ->label('تاريخ الإنتاج')
                            ->required(),
                        
                        DatePicker::make('expiry_date')
                            ->label('تاريخ انتهاء الصلاحية')
                            ->required(),
                        
                        FileUpload::make('image_url')
                            ->label('صورة المنتج')
                            ->image()
                            ->directory('products')
                            ->columnSpanFull(),
                        
                        Toggle::make('is_active')
                            ->label('نشط')
                            ->default(true),
                    ])->columns(2),
                    
                Forms\Components\Section::make('المكونات')
                    ->schema([
                        Forms\Components\CheckboxList::make('ingredients')
                            ->label('المكونات')
                            ->relationship('ingredients', 'name')
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_url')
                    ->label('الصورة')
                    ->circular(),
                    
                TextColumn::make('name')
                    ->label('اسم المنتج')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('category.name')
                    ->label('الفئة')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('brand.name')
                    ->label('العلامة التجارية')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('production_date')
                    ->label('تاريخ الإنتاج')
                    ->date('Y-m-d')
                    ->sortable(),
                    
                TextColumn::make('expiry_date')
                    ->label('تاريخ انتهاء الصلاحية')
                    ->date('Y-m-d')
                    ->sortable(),
                    
                TextColumn::make('average_rating')
                    ->label('متوسط التقييم')
                    ->numeric(1),
                    
                ToggleColumn::make('is_active')
                    ->label('نشط'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('الفئة')
                    ->relationship('category', 'name'),
                    
                Tables\Filters\SelectFilter::make('brand')
                    ->label('العلامة التجارية')
                    ->relationship('brand', 'name'),
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
            RelationManagers\PackagesRelationManager::make(),
            RelationManagers\IngredientsRelationManager::make(),
            RelationManagers\ReviewsRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
