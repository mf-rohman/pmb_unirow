<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TokenResource\Pages;
use App\Filament\Resources\TokenResource\RelationManagers;
use App\Models\Token;
use Dotenv\Util\Str as UtilStr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class TokenResource extends Resource
{
    protected static ?string $model = Token::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Generate Token')
                ->schema([
                    // Input Kode (Bisa manual atau generate otomatis)
                    Forms\Components\TextInput::make('kode')
                        ->default(strtoupper(Str::random(6))) // Auto-fill random
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(20),

                    Forms\Components\TextInput::make('nama_guru_bk')
                        ->label('Nama Guru BK / Koordinator'),

                    Forms\Components\TextInput::make('asal_sekolah')
                        ->label('Asal Sekolah Target'),

                    Forms\Components\DatePicker::make('expired_at')
                        ->label('Berlaku Sampai')
                        ->required()
                        ->minDate(now()),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                ->weight('bold')
                ->copyable() // Agar admin mudah copy tokennya
                ->searchable(),

                Tables\Columns\TextColumn::make('nama_guru_bk')
                    ->label('Guru BK')
                    ->searchable(),

                Tables\Columns\TextColumn::make('is_used')
                    ->badge()
                    ->color(fn (string $state): string => $state ? 'danger' : 'success')
                    ->formatStateUsing(fn (string $state) => $state ? 'Terpakai' : 'Aktif')
                    ->label('Status'),

                Tables\Columns\TextColumn::make('pendaftar.nama_lengkap')
                    ->label('Dipakai Oleh')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('expired_at')
                    ->date('d M Y')
                    ->label('Expired'),
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
            'index' => Pages\ListTokens::route('/'),
            'create' => Pages\CreateToken::route('/create'),
            'edit' => Pages\EditToken::route('/{record}/edit'),
        ];
    }
}
