<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationGroup = 'Manajemen Produk';
    protected static ?string $navigationLabel = 'Ulasan Pelanggan';
    protected static ?string $modelLabel = 'Ulasan';
    protected static ?string $pluralModelLabel = 'Ulasan Pelanggan';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Pelanggan')
                    ->disabled(),
                Forms\Components\Select::make('package_id')
                    ->relationship('package', 'name')
                    ->label('Produk')
                    ->disabled(),
                Forms\Components\TextInput::make('order_id')
                    ->label('Nomor Pesanan')
                    ->formatStateUsing(fn ($record) => $record?->order?->order_number)
                    ->disabled(),
                Forms\Components\TextInput::make('rating')
                    ->label('Bintang (1-5)')
                    ->numeric()
                    ->disabled(),
                Forms\Components\Textarea::make('comment')
                    ->label('Komentar')
                    ->columnSpanFull()
                    ->disabled(),
                Forms\Components\FileUpload::make('images')
                    ->label('Foto Ulasan')
                    ->multiple()
                    ->image()
                    ->disabled()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_visible')
                    ->label('Tampilkan di Publik (Katalog)')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('package.name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Bintang')
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => str_repeat('⭐', (int)$state)),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Komentar')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_visible')
                    ->label('Tampil di Publik')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            // 'create' => Pages\CreateReview::route('/create'), // Disable create since users make them
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false; // Admin cannot create fake reviews
    }
}
