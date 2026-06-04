<?php

namespace App\Filament\Pages\Settings;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Page;
use Spatie\LaravelSettings\Settings;

abstract class SettingsPage extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
    use InteractsWithFormActions;
    use HasUnsavedDataChangesAlert;

    protected static string $view = 'filament.settings.page';

    protected ?Settings $settings = null;

    protected static ?string $navigationGroup = 'Settings';

    protected static bool $shouldRegisterNavigation = true;

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save settings')
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }

    protected function getSettingsInstance(): Settings
    {
        return $this->settings ??= app(static::getSettingsClass());
    }

    public function mount(): void
    {
        $this->settings = app(static::getSettingsClass());
        $this->form->fill($this->settings->toArray());
    }

    public function save(): void
    {
        $this->getSettingsInstance()->fill($this->form->getState())->save();

        $this->notify('success', 'Settings saved successfully.');
    }

    abstract protected static function getSettingsClass(): string;
}
