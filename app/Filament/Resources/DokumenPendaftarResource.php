<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DokumenPendaftarResource\Pages;
use App\Models\DokumenPendaftar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class DokumenPendaftarResource extends Resource
{
    protected static ?string $model = DokumenPendaftar::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Penerimaan';
    protected static ?string $navigationLabel = 'Semua Dokumen';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dokumen')
                    ->schema([
                        // Pilih Pendaftar (Relasi)
                        Forms\Components\Select::make('pendaftar_id')
                            ->relationship('pendaftar', 'nama_lengkap')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Nama Pendaftar'),

                        Forms\Components\TextInput::make('jenis_dokumen')
                            ->required()
                            ->maxLength(255)
                            ->label('Jenis Dokumen'),

                        // File Upload / Preview
                        Forms\Components\FileUpload::make('path_file')
                            ->label('File')
                            ->directory('dokumen-pendaftar')
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),

                        // Status Verifikasi
                        Forms\Components\Select::make('status_verifikasi')
                            ->options([
                                'menunggu' => 'Menunggu Verifikasi',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak',
                            ])
                            ->required()
                            ->native(false)
                            ->label('Status Verifikasi'),

                        Forms\Components\Textarea::make('catatan_verifikasi')
                            ->label('Catatan (Jika Ditolak)')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Nama Pendaftar
                Tables\Columns\TextColumn::make('pendaftar.nama_lengkap')
                    ->label('Nama Pendaftar')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // Jenis Dokumen
                Tables\Columns\TextColumn::make('jenis_dokumen')
                    ->label('Jenis')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Sertifikat Hafalan' => 'warning',
                        'Sertifikat Prestasi' => 'info',
                        default => 'gray',
                    }),

                // Nama File Asli
                Tables\Columns\TextColumn::make('nama_asli_file')
                    ->label('Nama File')
                    ->limit(20)
                    ->icon('heroicon-o-document'),

                // Status Badge
                Tables\Columns\TextColumn::make('status_verifikasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Terakhir Update'),
            ])
            ->filters([
                // Filter berdasarkan Status
                Tables\Filters\SelectFilter::make('status_verifikasi')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ]),
                
                // Filter berdasarkan Jenis Dokumen (Hanya menampilkan dokumen khusus)
                Tables\Filters\SelectFilter::make('jenis_dokumen')
                    ->options([
                        'Ijazah' => 'Ijazah',
                        'KTP' => 'KTP',
                        'Sertifikat Hafalan' => 'Sertifikat Hafalan',
                        'Sertifikat Prestasi' => 'Sertifikat Prestasi',
                    ]),
            ])
            ->actions([
                // Tombol Lihat File
                Tables\Actions\Action::make('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn (DokumenPendaftar $record) => Storage::url($record->path_file))
                    ->openUrlInNewTab(),

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
            'index' => Pages\ListDokumenPendaftars::route('/'),
            'create' => Pages\CreateDokumenPendaftar::route('/create'),
            'edit' => Pages\EditDokumenPendaftar::route('/{record}/edit'),
        ];
    }
}