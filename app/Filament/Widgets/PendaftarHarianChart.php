<?php

namespace App\Filament\Widgets;

use App\Models\Pendaftar;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PendaftarHarianChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Pendaftaran (30 Hari Terakhir)';
    
    // Urutan tampilan (paling bawah)
    protected static ?int $sort = 3;
    
    // Agar chart ini lebar (memenuhi layar)
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Menggunakan package Flowframe Trend (Standard Filament)
        // Pastikan sudah install: composer require flowframe/laravel-trend
        $data = Trend::model(Pendaftar::class)
            ->between(
                start: now()->subDays(30),
                end: now(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pendaftar Baru',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#5D5FEF', // Warna garis (Ungu tema MABA)
                    'fill' => true, // Area di bawah garis diwarnai
                    'backgroundColor' => 'rgba(93, 95, 239, 0.1)', // Warna area transparan
                    'tension' => 0.4, // Membuat garis melengkung halus (curved)
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        // Pilihan Chart: 'line' sangat cocok untuk data time-series (waktu)
        return 'line';
    }
}