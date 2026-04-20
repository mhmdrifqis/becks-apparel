<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReturnRequestResource\Pages;
use App\Models\ReturnRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReturnRequestResource extends Resource
{
    protected static ?string $model = ReturnRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationLabel = 'Manajemen Retur';

    protected static ?string $modelLabel = 'Pengajuan Retur';

    protected static ?string $pluralModelLabel = 'Pengajuan Retur';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Retur')
                    ->schema([
                        Forms\Components\Select::make('order_id')
                            ->label('No. Order')
                            ->relationship('order', 'order_number')
                            ->searchable()
                            ->required()
                            ->disabledOn('edit'),

                        Forms\Components\Select::make('user_id')
                            ->label('Pelanggan')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required()
                            ->disabledOn('edit'),

                        Forms\Components\Textarea::make('reason')
                            ->label('Alasan Retur')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('proof_images')
                            ->label('Bukti Foto')
                            ->multiple()
                            ->image()
                            ->directory('returns')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Validasi Admin')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status Validasi')
                            ->options([
                                'pending' => 'Pending Review',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                'completed' => 'Retur Selesai',
                            ])
                            ->required(),

                        Forms\Components\Textarea::make('admin_note')
                            ->label('Catatan Admin')
                            ->rows(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('No. Order')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'info' => 'completed',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'completed' => 'Selesai',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Diajukan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'completed' => 'Completed',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Validasi'),
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
            'index' => Pages\ListReturnRequests::route('/'),
            'create' => Pages\CreateReturnRequest::route('/create'),
            'edit' => Pages\EditReturnRequest::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'danger';
    }
}
