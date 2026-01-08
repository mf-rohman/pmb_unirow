<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftarResource\Pages;
// use App\Filament\Resources\PendaftarResource\RelationManagers;
use App\Filament\Resources\PendaftarResource\RelationManagers\DokumenPendaftarsRelationManager;
use App\Models\Pendaftar;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\JalurPendaftaran;
use App\Models\User;
use App\Models\Village;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;

class PendaftarResource extends Resource
{
    protected static ?string $model = \App\Models\Pendaftar::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Penerimaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('No Pendaftaran')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('no_pendaftaran')
                                ->disabled()
                                ->dehydrated(false),

                            Select::make('status')
                                ->label('Status Kelulusan')
                                ->options([
                                    'baru' => 'Baru Mendaftar',
                                    'verifikasi_data' => 'Verifikasi Data',
                                    'lulus' => 'LULUS',
                                    'gagal' => 'TIDAK LULUS',
                                ])
                                ->required()
                                ->native(false),

                            Select::make('jalur_pendaftaran_id')
                                ->relationship('jalurPendaftaran', 'nama_jalur')
                                // ->searchable()
                                ->required()
                                // ->disabled()
                                ->label('Jalur'),
                        ]),

                        Grid::make(2)->schema([
                            Select::make('program_studi_id_1')
                                ->relationship('programStudi1', 'nama_prodi')
                                ->label('Pilihan 1')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('program_studi_id_2')
                                ->relationship('programStudi2', 'nama_prodi')
                                ->label('Pilihan 2')
                                ->disabled(),
                        ]),
                    ])
                    ->collapsed(),
                
                Section::make('Biodata Calon Mahasiswa')
                    ->schema([
                        TextInput::make('nama_lengkap')->required(),
                        TextInput::make('email')
                            ->label('Email (Untuk Login)')
                            ->email()
                            ->required()
                            ->unique(User::class, 'email', ignoreRecord:true)
                            ->visibleOn('create'),

                        TextInput::make('email_readonly')
                            ->label('Email User')
                            ->disabled()
                            ->dehydrated(false)
                            ->visibleOn('edit')
                            ->formatStateUsing(fn ($record) => $record->user->email ?? '-'),
                        
                        TextInput::make('nik')->label('NIK')->required(),
                        TextInput::make('nisn')->label('NISN')->required(),
                        TextInput::make('no_hp')->label('No HP/WA')->required(),
                        TextInput::make('tempat_lahir')->required(),
                        DatePicker::make('tanggal_lahir')->required(),

                        Select::make('jenis_kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ]),

                        Select::make('agama')
                            ->options([
                                'Islam' => 'Islam',
                                'Kristen' => 'Kristen',
                                'Katolik' => 'Katolik',
                                'Hindu' => 'Hindu',
                                'Budha' => 'Budha',
                                'Konguchu' => 'Konguchu',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->live()
                            ->required(),

                        TextInput::make('agama_input')
                            ->label('Agama (Lainnya)')
                            ->visible(fn ($get) => $get('agama') === 'Lainnya')
                            ->required(fn ($get) => $get('agama') === 'Lainnya')
                            ->dehydrated(false) // tidak disimpan ke DB
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                if ($get('agama') === 'lainnya') {
                                    $set('agama', $state);
                                }
                            }),

                    ])->columns(2)
                    ->collapsed(),

                Section::make('Alamat Domisili')
                    ->schema([
                        Textarea::make('alamat_lengkap')
                            ->label('Jalan/Dusun')
                            ->columnSpanFull(),
                        
                        Grid::make(2)->schema([
                            TextInput::make('rt')->label('RT'),
                            TextInput::make('rw')->label('RW'),
                        ]),

                        Select::make('province_id')
                            ->label('Provinsi')
                            ->options(Province::all()->pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('regency_id', null)),

                        Select::make('regency_id')
                            ->label('Kabupaten/Kota')
                            ->options(fn (Get $get): Collection => Regency::query()
                                ->where('province_id', $get ('province_id'))
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('district_id', null)),

                        Select::make('district_id')
                            ->label('Kecamatan')
                            ->options(fn (Get $get): Collection => District::query()
                                ->where('regency_id', $get ('regency_id'))
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) =>$set ('village_id', null)),

                        Select::make('village_id')
                            ->label('Desa/Kelurahan')
                            ->options(fn (Get $get): Collection => Village::query()
                                ->where('district_id', $get ('district_id'))
                                ->pluck('name', 'id'))
                            ->searchable(),
                            // ->live()
                    ])->columns(2)
                    ->collapsed(),

                Section::make('Data Sekolah dan Orang Tua')
                    ->schema([
                        TextInput::make('asal_sekolah'),
                        TextInput::make('jurusan_asal_sekolah'),
                        TextInput::make('nama_ibu_kandung'),
                        TextInput::make('nama_ayah_kandung'),
                    ])->columns(2)
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_pendaftaran')
                    ->searchable()
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('nik')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nisn')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('nama_lengkap')
                    ->searchable()
                    // ->weight('bold')
                    ->sortable(),

                TextColumn::make('gelombang.nama_gelombang')
                    ->label('Gelombang')
                    ->sortable()
                    ->badge(),


                TextColumn::make('jalurPendaftaran.nama_jalur')
                    ->label('Jalur')
                    ->sortable()
                    ->badge(),
                
                TextColumn::make('programStudi1.singkatan')
                    ->label('Prodi 1')
                    ->badge()
                    ->color('info'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'baru' => 'info',
                        'verifikasi_data' => 'warning',
                        'lulus' => 'success',
                        'gagal' => 'danger',
                        default => 'gray', 
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft / Belum Upload',
                        'baru' => 'Baru',
                        'verifikasi_data' => 'Verifikasi',
                        'lulus' => 'Lulus',
                        'gagal' => 'Gagal',
                    ]),

                SelectFilter::make('jalur_pendaftaran_id')
                    ->options(\App\Models\JalurPendaftaran::pluck('nama_jalur', 'id')),
                
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

    public static function getRelations(): array
    {
        return [
            DokumenPendaftarsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPendaftars::route('/'),
            'create' => Pages\CreatePendaftar::route('/create'),
            'view' => Pages\ViewPendaftar::route('/{record}'),
            'edit' => Pages\EditPendaftar::route('/{record}/edit'),
        ];
    }
}
