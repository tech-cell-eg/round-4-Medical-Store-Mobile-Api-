<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->label('المستخدم')
                    ->formatStateUsing(fn ($state, $record) => optional($record->user)->name ?? '---')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product_id')
                    ->label('المنتج')
                    ->formatStateUsing(fn ($state, $record) => optional($record->product)->name ?? '---')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('التقييم')
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label('التعليق')
                    ->limit(30),
                Tables\Columns\TextColumn::make('reviewer_name')
                    ->label('اسم المقيّم')
                    ->default('---'),
                Tables\Columns\TextColumn::make('review_date')
                    ->label('تاريخ التقييم')
                    ->date('Y-m-d'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
