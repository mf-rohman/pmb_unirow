<?php

namespace App\Filament\Resources\PendaftarResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class DokumenPendaftarsRelationManager extends RelationManager
{
    protected static string $relationship = 'dokumenPendaftars';

    protected static ?string $title = 'Verifikasi Dokumen';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('jenis_dokumen')
                    ->disabled()
                    // ->required()
                    ->label('Jenis Dokumen'),

                FileUpload::make('path_file')
                    ->label('File Upload User')
                    ->directory('dokumen-pendaftar')
                    ->openable()
                    ->downloadable()
                    ->disabled()
                    ->columnSpanFull(),

                Select::make('status_verifikasi')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ])
                    ->required()
                    ->label('Status Verifikasi')
                    ->native(false),

                Textarea::make('catatan_verifikasi')
                    ->label('Catatan (Jika Ditolak)')
                    ->placeholder('Contoh: Foto buram, harap upload ulang')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('jenis_dokumen')
            ->columns([
                Tables\Columns\TextColumn::make('jenis_dokumen')
                    ->label('Dokumen')
                    ->sortable(),
                    
                TextColumn::make('nama_asli_file')
                    ->label('Nama File')
                    ->limit(20),

                TextColumn::make('status_verifikasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu' => 'gray',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                    }),

                TextColumn::make('catatan_verifikasi')
                    ->limit(30),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Verifikasi')
                    ->modalHeading('Verifikasi Dokumen'),

                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => Storage::url($record->path_file))
                    ->openUrlInNewTab(),
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
