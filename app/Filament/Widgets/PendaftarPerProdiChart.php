<?php

namespace App\Filament\Widgets;

use App\Models\Pendaftar;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PendaftarPerProdiChart extends ChartWidget
{
    protected static ?string $heading = 'Pendaftar per Program Studi';
    
    // Urutan tampilan di dashboard (makin besar makin bawah)
    protected static ?int $sort = 2; 

    protected function getData(): array
    {
        // Query untuk menghitung pendaftar per prodi (Pilihan 1)
        // Kita join ke tabel program_studis untuk ambil namanya
        $data = Pendaftar::select('program_studis.nama_prodi', DB::raw('count(*) as total'))
            ->join('program_studis', 'pendaftars.program_studi_id_1', '=', 'program_studis.kode_prodi')
            ->groupBy('program_studis.nama_prodi')
            ->orderByDesc('total')
            ->limit(5) // Ambil 5 prodi teratas
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pendaftar',
                    'data' => $data->pluck('total'),
                    'backgroundColor' => [
                        '#36A2EB', '#FF6384', '#4BC0C0', '#FF9F40', '#9966FF'
                    ],
                ],
            ],
            'labels' => $data->pluck('nama_prodi'),
        ];
    }

    protected function getType(): string
    {
        // Pilihan Chart: 'doughnut', 'pie', 'bar', 'line', 'polarArea', 'radar'
        return 'doughnut'; 
    }
}