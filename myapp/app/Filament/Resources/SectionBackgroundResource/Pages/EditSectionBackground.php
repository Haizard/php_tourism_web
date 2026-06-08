<?php

namespace App\Filament\Resources\SectionBackgroundResource\Pages;

use App\Filament\Resources\SectionBackgroundResource;
use Filament\Resources\Pages\EditRecord;

class EditSectionBackground extends EditRecord
{
    protected static string $resource = SectionBackgroundResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Map temporary form fields into `bg_value` to avoid type collisions
        // between the color picker (string) and the file upload (string/array).
        if (isset($data['bg_type'])) {
            if ($data['bg_type'] === 'color') {
                $data['bg_value'] = $data['bg_color'] ?? $data['bg_value'] ?? null;
            } elseif ($data['bg_type'] === 'image') {
                $data['bg_value'] = $data['bg_image'] ?? $data['bg_value'] ?? null;
            } else {
                $data['bg_value'] = null;
            }
        }

        // Remove temporary keys so they don't try to be written as columns.
        unset($data['bg_color'], $data['bg_image']);

        return $data;
    }
}
