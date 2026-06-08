<?php

namespace App\Filament\Pages;

use App\Settings\ThemeSettings;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Validate;

class CssEditor extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-code-bracket';
    protected static ?string $navigationLabel = 'CSS Editor';
    protected static ?string $navigationGroup = 'Appearance';
    protected static ?int    $navigationSort  = 10;
    protected static ?string $title           = 'Custom CSS Editor';
    protected static string  $view            = 'filament.pages.css-editor';
    protected static ?string $slug            = 'css-editor';

    public string $css = '';
    public string $activeTab = 'snippets';

    public function mount(): void
    {
        $settings = app(ThemeSettings::class);
        $this->css = $settings->customCss ?? '';
    }

    public function save(): void
    {
        $settings = app(ThemeSettings::class);
        $settings->customCss = $this->css;
        $settings->save();

        Notification::make()->title('Custom CSS saved successfully.')->success()->send();
    }

    public function insertSnippet(string $snippet): void
    {
        $this->css = rtrim($this->css) . "\n\n" . $snippet;
    }
}
