<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReturnRequestResource\Pages;
use App\Models\ReturnRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
class ReturnRequestResource extends Resource
{
    protected static ?string $model = ReturnRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationGroup = 'Operasional';

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
                            ->disabledOn('edit')
                            ->columnSpanFull(),

                    ])->columns(2),

                Forms\Components\Section::make('Lampiran Bukti Pelanggan')
                    ->description('Klik gambar untuk melihat ukuran asli dan unduh bukti bila diperlukan.')
                    ->schema([
                        Forms\Components\Placeholder::make('proof_gallery')
                            ->label('Foto / Video Bukti')
                            ->content(function (?ReturnRequest $record): HtmlString {
                                $files = array_values(array_filter($record?->proof_images ?? []));
                                if ($files === []) return new HtmlString('<span style="font-size:14px;color:#6b7280">Belum ada lampiran bukti dari pelanggan.</span>');
                                $html = '<div style="display:flex;flex-wrap:wrap;gap:12px">';
                                foreach ($files as $index => $file) {
                                    $url = e(Storage::disk('public')->url($file));
                                    $filename = e(basename($file));
                                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                    $preview = in_array($extension, ['mp4', 'mov', 'avi', 'webm'], true) ? '<div style="display:flex;width:100px;height:100px;align-items:center;justify-content:center;border-radius:8px;background:#111827;color:#fff;font-size:11px">Video</div>' : '<img src="' . $url . '" alt="Bukti retur ' . ($index + 1) . '" style="display:block;width:100px;height:100px;object-fit:cover;border-radius:8px">';
                                    $html .= '<div style="width:124px;border:1px solid #e5e7eb;border-radius:12px;background:#fff;padding:8px"><a href="' . $url . '" target="_blank" rel="noopener" title="Buka ukuran asli">' . $preview . '</a><a href="' . $url . '" download style="display:block;text-align:center;margin-top:6px;font-size:10px;font-weight:600;color:#2563eb">Unduh</a></div>';
                                }
                                return new HtmlString($html . '</div>');
                            })
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (?ReturnRequest $record): bool => $record !== null),

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

                Tables\Columns\ImageColumn::make('proof_images')
                    ->label('Lampiran')
                    ->stacked()
                    ->limit(3)
                    ->square(),

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
                    ->dateTime('d M Y, H:i')
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
                Tables\Actions\EditAction::make()
                    ->label('Validasi')
                    ->tooltip('Validasi Retur')
                    ->icon('heroicon-o-check-badge')
                    ->iconButton()
                    ->slideOver()
                    ->modalWidth(\Filament\Support\Enums\MaxWidth::ExtraLarge)
                    ->modalSubmitActionLabel('Simpan Validasi'),
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
