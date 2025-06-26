<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'المنتجات والمخزون';

    protected static ?string $navigationLabel = 'الفئات';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('معلومات الفئة')
                    ->schema([
                        TextInput::make('name')
                            ->label('اسم الفئة')
                            ->required()
                            ->maxLength(255),
                            
                        Select::make('parent_id')
                            ->label('الفئة الأب')
                            ->relationship('parent', 'name')
                            ->options(Category::whereNull('parent_id')->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('اختر الفئة الأب (اختياري)')
                            ->helperText('اترك هذا الحقل فارغًا إذا كانت هذه فئة رئيسية'),

                        Textarea::make('description')
                            ->label('وصف الفئة')
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        FileUpload::make('image_url')
                            ->label('صورة الفئة')
                            ->image()
                            ->imageEditor()
                            ->directory('categories')
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
                ImageColumn::make('image_url')
                    ->label('الصورة')
                    ->circular(),

                TextColumn::make('name')
                    ->label('اسم الفئة')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('parent.name')
                    ->label('الفئة الأب')
                    ->formatStateUsing(fn ($state) => $state ?: 'فئة رئيسية')
                    ->searchable(),

                TextColumn::make('description')
                    ->label('الوصف')
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('products_count')
                    ->label('عدد المنتجات')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('نوع الفئة')
                    ->options([
                        '' => 'الكل',
                        'main' => 'الفئات الرئيسية',
                        'sub' => 'الفئات الفرعية',
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['value'] === 'main') {
                            return $query->whereNull('parent_id');
                        }
                        
                        if ($data['value'] === 'sub') {
                            return $query->whereNotNull('parent_id');
                        }
                        
                        return $query;
                    }),
                    
                Tables\Filters\SelectFilter::make('parent_category')
                    ->label('الفئة الأب')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
