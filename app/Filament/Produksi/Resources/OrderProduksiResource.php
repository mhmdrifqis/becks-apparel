<?php

namespace App\Filament\Produksi\Resources;

use App\Filament\Produksi\Resources\OrderProduksiResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;

class OrderProduksiResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Antrean Produksi';

    protected static ?string $modelLabel = 'Pesanan Produksi';

    protected static ?string $pluralModelLabel = 'Antrean Produksi';

    protected static ?string $navigationGroup = 'Workshop';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Detail Pesanan')
                    ->schema([
                        Infolists\Components\TextEntry::make('order_number')->label('No. Order'),
                        Infolists\Components\TextEntry::make('user.name')->label('Nama Pemesan'),
                        Infolists\Components\TextEntry::make('status')->badge(),
                    ])->columns(3),

                Infolists\Components\Section::make('Detail Teknis & Desain')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('orderItems')
                            ->label('Daftar Produk')
                            ->schema([
                                Infolists\Components\Grid::make(3)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('package.name')->label('Paket'),
                                        Infolists\Components\TextEntry::make('material.name')->label('Bahan'),
                                        Infolists\Components\TextEntry::make('quantity')->label('Qty'),
                                    ]),
                                Infolists\Components\TextEntry::make('design.preview_path')
                                    ->label('Preview & Link Desain')
                                    ->formatStateUsing(fn ($record) => $record && $record->design ? 
                                        new \Illuminate\Support\HtmlString('
                                            <div class="space-y-2">
                                                <img src="' . Storage::url($record->design->preview_path) . '" class="w-full max-w-[200px] rounded-xl border border-slate-200 shadow-sm" />
                                                <a href="' . Storage::url($record->design->preview_path) . '" target="_blank" class="inline-flex items-center text-xs font-bold text-primary-600 hover:underline">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                    Download File Desain (HQ)
                                                </a>
                                            </div>
                                        ') : 'Tidak ada desain'
                                    ),
                            ])
                    ])
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Produksi')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('No. Order')
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->label('Update Progress Produksi')
                            ->options([
                                'paid'      => 'Antrean Masuk',
                                'printing'  => 'Proses Cetak',
                                'sewing'    => 'Proses Jahit',
                                'qc'        => 'Quality Control',
                                'ready'     => 'Selesai Produksi (Siap Kirim)',
                            ])
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->whereIn('status', ['paid', 'printing', 'sewing', 'qc', 'ready'])
                    ->whereIn('payment_status', ['paid', 'partial'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Order')
                    ->searchable()
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pemesan')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status Saat Ini')
                    ->colors([
                        'success' => 'paid',
                        'info'    => 'printing',
                        'warning' => 'sewing',
                        'warning' => 'qc',
                        'primary' => 'ready',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'paid'     => 'Antrean',
                        'printing' => 'Cetak',
                        'sewing'   => 'Jahit',
                        'qc'       => 'QC',
                        'ready'    => 'Selesai',
                        default    => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Bayar')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Tahapan')
                    ->options([
                        'paid'     => 'Antrean',
                        'printing' => 'Cetak',
                        'sewing'   => 'Jahit',
                        'qc'       => 'QC',
                        'ready'    => 'Selesai',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Detail'),
                Tables\Actions\EditAction::make()->label('Update'),
                
                // FITUR: Catat Pemakaian Bahan
                Tables\Actions\Action::make('log_material_usage')
                    ->label('Log Bahan')
                    ->icon('heroicon-m-beaker')
                    ->color('info')
                    ->form([
                        Forms\Components\Select::make('order_item_id')
                            ->label('Pilih Produk (Item)')
                            ->options(fn ($record) => $record->orderItems->mapWithKeys(function ($item) {
                                return [$item->id => $item->package->name . ' (' . $item->material->name . ')'];
                            }))
                            ->required(),
                        Forms\Components\TextInput::make('usage')
                            ->label('Jumlah Pemakaian')
                            ->numeric()
                            ->required()
                            ->helperText('Angka ini akan langsung memotong stok di gudang.'),
                    ])
                    ->action(function (\App\Models\Order $record, array $data) {
                        $item = \App\Models\OrderItem::find($data['order_item_id']);
                        if ($item && $item->material) {
                            $item->increment('material_usage', $data['usage']);
                            $item->material->decrement('stock', $data['usage']);

                            \Filament\Notifications\Notification::make()
                                ->title('Stok Terpotong')
                                ->body("Pemakaian {$data['usage']} {$item->material->unit} tercatat.")
                                ->success()
                                ->send();
                        }
                    })
                    ->modalHeading('Input Pemakaian Bahan Baku')
                    ->modalSubmitActionLabel('Potong Stok Sekarang'),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            // Relation manager untuk daftar item (ukuran, desain) akan ditambahkan nanti
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrderProduksi::route('/'),
            'view' => Pages\ViewOrderProduksi::route('/{record}'),
            'edit' => Pages\EditOrderProduksi::route('/{record}/edit'),
        ];
    }
}
