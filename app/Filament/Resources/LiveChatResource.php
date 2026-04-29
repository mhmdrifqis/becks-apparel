<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LiveChatResource\Pages;
use App\Filament\Resources\LiveChatResource\RelationManagers;
use App\Models\LiveChat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LiveChatResource extends Resource
{
    protected static ?string $model = LiveChat::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Customer Support';
    protected static ?string $navigationLabel = 'Riwayat Chat';
    protected static ?string $pluralLabel = 'Riwayat Chat';
    protected static ?string $modelLabel = 'Riwayat Chat';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_name')
                    ->disabled()
                    ->label('Nama Pengguna'),
                Forms\Components\TextInput::make('user_id')
                    ->disabled()
                    ->label('User ID / Session ID'),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'closed' => 'Selesai',
                    ])
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_name')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'closed' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'closed' => 'Selesai',
                    }),
                TextColumn::make('messages_count')
                    ->counts('messages')
                    ->label('Jumlah Pesan'),
                TextColumn::make('created_at')
                    ->label('Waktu Mulai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Aktif',
                        'closed' => 'Selesai',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Riwayat Chat?')
                    ->modalDescription('Apakah Anda yakin ingin menghapus seluruh riwayat percakapan ini? Tindakan ini tidak dapat dibatalkan.')
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\MessagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLiveChats::route('/'),
            'view' => Pages\ViewLiveChat::route('/{record}'),
        ];
    }
}
