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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Facades\Storage;

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
                        
                        TextInput::make('new_price')
                            ->label('السعر الجديد')
                            ->numeric()
                            ->required()
                            ->minValue(0),
                            
                        TextInput::make('old_price')
                            ->label('السعر القديم')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('اتركه فارغاً إذا لم يكن هناك سعر قديم'),
                        
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
                        
                        // عرض الصورة الحالية إذا كانت موجودة
                        ViewField::make('current_image')
                            ->label('الصورة الحالية')
                            ->view('components.current-logo', [
                                'url' => fn ($record) => $record && $record->image_url ? url('storage/' . $record->image_url) : null,
                                'filename' => fn ($record) => $record && $record->image_url ? basename($record->image_url) : null,
                            ])
                            ->visible(fn ($record) => $record && $record->image_url)
                            ->columnSpanFull(),
                        
                        // مكون رفع الملف المخصص
                        ViewField::make('image_upload')
                            ->label('صورة المنتج')
                            ->view('components.file-upload-field', [
                                'name' => 'image_url',
                                'label' => 'صورة المنتج',
                                'accept' => 'image/*',
                                'maxSize' => 2048,
                                'required' => false,
                                'helpText' => 'يجب أن تكون الصورة بصيغة JPG أو PNG أو WEBP بحجم أقصى 2 ميجابايت',
                            ])
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
                ImageColumn::make('image_url_full')
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
                    ->formatStateUsing(fn ($state, $record) => $record->brand?->name ?? 'لا يوجد')
                    ->searchable(),
                    
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

                TextColumn::make('unit.name')
                    ->label('الوحدة')
                    ->searchable(),

                TextColumn::make('new_price')
                    ->label('السعر الجديد')
                    ->money('EGP'),
                    
                TextColumn::make('old_price')
                    ->label('السعر القديم')
                    ->money('EGP')
                    ->formatStateUsing(fn($state) => $state ?? '-'),

                TextColumn::make('ingredients')
                    ->label('المكونات')
                    ->formatStateUsing(fn($state, $record) => $record->ingredients->pluck('name')->implode(', ')),

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
            // تم إزالة مدير العلاقة مع العبوات بناءً على طلب مالك المنتج
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
