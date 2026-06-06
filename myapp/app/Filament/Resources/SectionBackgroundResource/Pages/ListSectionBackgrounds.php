<?php

namespace App\Filament\Resources\SectionBackgroundResource\Pages;

use App\Filament\Resources\SectionBackgroundResource;
use Filament\Resources\Pages\ListRecords;

class ListSectionBackgrounds extends ListRecords
{
    protected static string $resource = SectionBackgroundResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
