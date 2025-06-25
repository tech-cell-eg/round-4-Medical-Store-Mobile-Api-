<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackagesRelationManager extends RelationManager
{
    protected static string $relationship = 'packages';

    protected static ?string $title = 'العبوات';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم العبوة')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('size')
                    ->label('حجم العبوة')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->label('السعر')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                Forms\Components\TextInput::make('quantity')
                    ->label('الكمية')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Forms\Components\TextInput::make('discount')
                    ->label('الخصم')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%')
                    ->default(0),
                Forms\Components\TextInput::make('sku')
                    ->label('رمز التخزين (SKU)')
                    ->maxLength(255)
                    ->default(fn() => 'SKU-' . strtoupper(uniqid() . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4))),
                Forms\Components\TextInput::make('barcode')
                    ->label('الباركود')
                    ->maxLength(255)
                    ->default(fn() => str_pad(mt_rand(1, 999999999999), 12, '0', STR_PAD_LEFT)),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم العبوة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('size')
                    ->label('حجم العبوة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('رمز التخزين (SKU)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('barcode')
                    ->label('الباركود')
                    ->searchable(),
                Tables\Columns\TextColumn::make('size')
                    ->label('حجم العبوة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('barcode')
                    ->label('الباركود')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('السعر')
                    ->money('EGP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('الكمية')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount')
                    ->label('الخصم')
                    ->numeric()
                    ->suffix('%')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
