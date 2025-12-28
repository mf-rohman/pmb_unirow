<?php

namespace App\Filament\Resources\PendaftarResource\Pages;

use App\Filament\Resources\PendaftarResource;
use App\Models\User;
use App\Models\Gelombang; // <-- Jangan lupa import Model Gelombang
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class CreatePendaftar extends CreateRecord
{
    protected static string $resource = PendaftarResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // --- 1. CARI GELOMBANG AKTIF (FIX ERROR ANDA) ---
        $gelombangAktif = Gelombang::where('is_active', true)->first();

        if (!$gelombangAktif) {
            // Jika tidak ada gelombang aktif, hentikan proses dan beri notifikasi
            Notification::make()
                ->danger()
                ->title('Gagal Menyimpan')
                ->body('Tidak ada Gelombang Pendaftaran yang sedang aktif. Mohon aktifkan gelombang terlebih dahulu di menu Data Master.')
                ->persistent()
                ->send();
            
            $this->halt(); // Stop proses create
        }

        // Masukkan ID gelombang ke data yang akan disimpan
        $data['gelombang_id'] = $gelombangAktif->id;

        // --- 2. LOGIKA USER (YANG SEBELUMNYA) ---
        $existingUser = User::where('email', $data['email'])->first();
        
        if ($existingUser) {
            $userId = $existingUser->id;
            Notification::make()
                ->warning()
                ->title('User ditemukan')
                ->body('Email sudah terdaftar, data akan ditautkan ke user tersebut.')
                ->send();
        } else {
            $newUser = User::create([
                'name' => $data['nama_lengkap'],
                'email' => $data['email'],
                'password' => Hash::make('12345678'),
                'role' => 'user',
            ]);
            $userId = $newUser->id;
            Notification::make()->success()->title('User Baru Dibuat')->body('Password: 12345678')->send();
        }

        $data['user_id'] = $userId;
        
        // --- 3. GENERATE NO PENDAFTARAN ---
        $tahun = now()->format('Y');
        $data['no_pendaftaran'] = 'MABA-' . $tahun . '-' . str_pad($userId, 4, '0', STR_PAD_LEFT) . rand(100, 999);
        
        // Default status jika belum dipilih
        if (empty($data['status'])) {
            $data['status'] = 'baru';
        }

        return $data;
    }
}