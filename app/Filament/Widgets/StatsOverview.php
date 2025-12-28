<?php

namespace App\Filament\Widgets;

use App\Models\Pendaftar;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // Mengatur agar widget ini update otomatis setiap 15 detik (opsional)
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        return [
            // 1. Total Pendaftar
            Stat::make('Total Pendaftar', Pendaftar::count())
                ->description('Semua pendaftar masuk')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]), // Dummy chart kecil (hiasan)

            // 2. Pendaftar Hari Ini
            Stat::make('Pendaftar Hari Ini', Pendaftar::whereDate('created_at', today())->count())
                ->description('Pendaftaran baru hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            // 3. Status Lulus
            Stat::make('Peserta Lulus', Pendaftar::where('status', 'lulus')->count())
                ->description('Sudah diverifikasi & lulus')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
                
            // 4. Perlu Verifikasi
            Stat::make('Perlu Verifikasi', Pendaftar::where('status', 'verifikasi_data')->count())
                ->description('Menunggu tindakan admin')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}