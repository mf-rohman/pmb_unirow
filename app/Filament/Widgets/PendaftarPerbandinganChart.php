<?php

namespace App\Filament\Widgets;

use App\Models\Pendaftar;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class PendaftarPerbandinganChart extends ChartWidget
{
    protected static ?string $heading = 'Perbandingan Pendaftar (Tahun Ini vs Tahun Lalu)';
    
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // 1. DATA TAHUN INI (Current Year)
        // Kita ambil data bulan ini di tahun ini
        $dataTahunIni = Trend::model(Pendaftar::class)
            ->between(
                start: now()->startOfYear(), // Dari awal tahun (Januari)
                end: now()->endOfYear(),     // Sampai akhir tahun
            )
            ->perMonth() // Dikelompokkan per bulan
            ->count();

        // 2. DATA TAHUN LALU (Previous Year)
        // Kita ambil data dengan rentang waktu yang sama tapi tahun lalu
        $dataTahunLalu = Trend::model(Pendaftar::class)
            ->between(
                start: now()->subYear()->startOfYear(), // Awal tahun lalu
                end: now()->subYear()->endOfYear(),     // Akhir tahun lalu
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Tahun Ini (' . now()->year . ')',
                    'data' => $dataTahunIni->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#5D5FEF', // Warna Ungu Utama
                    'backgroundColor' => 'rgba(93, 95, 239, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Tahun Lalu (' . now()->subYear()->year . ')',
                    'data' => $dataTahunLalu->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#9CA3AF', // Warna Abu-abu (Pembanding)
                    'backgroundColor' => 'transparent',
                    'borderDash' => [5, 5], // Garis putus-putus biar beda
                    'fill' => false,
                    'tension' => 0.4,
                ],
            ],
            // Label bulan (Jan, Feb, Mar...)
            // Kita ambil dari data tahun ini saja karena bulannya sama
            'labels' => $dataTahunIni->map(fn (TrendValue $value) => Carbon::parse($value->date)->format('M')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}