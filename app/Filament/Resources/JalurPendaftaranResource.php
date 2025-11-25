<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JalurPendaftaranResource\Pages;
use App\Filament\Resources\JalurPendaftaranResource\RelationManagers;
use App\Models\JalurPendaftaran;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\View\Component;

class JalurPendaftaranResource extends Resource
{
    protected static ?string $model = JalurPendaftaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_jalur')
                    ->required()
                    ->unique(ignoreRecord: true),
                
                TextInput::make('nama_jalur')
                    ->required(),
                
                Select::make('kategori')
                    ->options([
                        'Reguler' => 'Reguler',
                        'Prestasi' => 'Prestasi',
                        'Hafidz' => 'Hafidz',
                        'Khusus' => 'Khusus/Lainnya',
                    ])
                    ->required(),

                Textarea::make('deskripsi')
                    ->columnSpanFull(),

                Forms\Components\Section::make('Keuntungan Jalur')
                    ->schema([
                        Toggle::make('bebas_tes_tulis')
                            ->label('Bebas Tes Tulis'),
                        
                        Toggle::make('free_biaya_pendaftaran')
                            ->label('Gratis Biaya Pendaftaran'),
                        
                        Toggle::make('free_daftar_ulang')
                            ->label('Gratis Daftar Ulang'),
                        
                        Toggle::make('free_dpp')
                            ->label('Gratis DPP'),
                    ])->columns(2),
                
                Toggle::make('is_active')
                    ->label('Aktifkan Jalur Ini')
                    ->default(true)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_jalur')->searchable(),
                TextColumn::make('nama_jalur')->searchable(),
                TextColumn::make('kategori')->sortable(),

                IconColumn::make('bebas_tes_tulis')
                    ->boolean()
                    ->label('Bebas Tes'),

                IconColumn::make('free_biaya_pendaftaran')
                    ->boolean()
                    ->label('Free Daftar'),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Status Aktif'),
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
            'index' => Pages\ListJalurPendaftarans::route('/'),
            'create' => Pages\CreateJalurPendaftaran::route('/create'),
            'edit' => Pages\EditJalurPendaftaran::route('/{record}/edit'),
        ];
    }
}
