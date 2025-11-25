<?php

namespace App\Filament\Resources\DokumenPendaftarResource\Pages;

use App\Filament\Resources\DokumenPendaftarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDokumenPendaftars extends ListRecords
{
    protected static string $resource = DokumenPendaftarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
