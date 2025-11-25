<?php

namespace App\Filament\Resources\DokumenPendaftarResource\Pages;

use App\Filament\Resources\DokumenPendaftarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDokumenPendaftar extends EditRecord
{
    protected static string $resource = DokumenPendaftarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
