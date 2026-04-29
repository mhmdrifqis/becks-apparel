<?php

namespace App\Filament\Resources\LiveChatResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';

    protected static ?string $title = 'Riwayat Percakapan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('message')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('message')
            ->columns([
                TextColumn::make('sender')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'user' => 'gray',
                        'admin' => 'info',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'user' => 'Pelanggan',
                        'admin' => 'Admin',
                    })
                    ->label('Pengirim'),
                TextColumn::make('message')
                    ->label('Pesan')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M H:i:s'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('clearMessages')
                    ->label('Kosongkan Semua Pesan')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->modalHeading('Kosongkan Pesan?')
                    ->modalDescription('Apakah Anda yakin ingin menghapus SEMUA pesan dalam sesi ini? Tindakan ini tidak dapat dibatalkan.')
                    ->action(fn () => $this->getOwnerRecord()->messages()->delete()),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Pesan?')
                    ->modalDescription('Apakah Anda yakin ingin menghapus pesan ini?'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
