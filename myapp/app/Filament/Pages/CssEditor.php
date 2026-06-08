<?php

namespace App\Filament\Pages;

use App\Settings\ThemeSettings;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class CssEditor extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-code-bracket';
    protected static ?string $navigationLabel = 'CSS Editor';
    protected static ?string $navigationGroup = 'Appearance';
    protected static ?int    $navigationSort  = 10;
    protected static ?string $title           = 'CSS Editor';
    protected static string  $view            = 'filament.pages.css-editor';
    protected static ?string $slug            = 'css-editor';

    public string $activePage = 'global';

    public string $customCss     = '';
    public string $homeCss       = '';
    public string $toursCss      = '';
    public string $tourDetailCss = '';
    public string $blogCss       = '';
    public string $contactCss    = '';
    public string $customPagesCss = '';

    public function mount(): void
    {
        $s = app(ThemeSettings::class);
        $this->customCss      = $s->customCss      ?? '';
        $this->homeCss        = $s->homeCss        ?? '';
        $this->toursCss       = $s->toursCss       ?? '';
        $this->tourDetailCss  = $s->tourDetailCss  ?? '';
        $this->blogCss        = $s->blogCss        ?? '';
        $this->contactCss     = $s->contactCss     ?? '';
        $this->customPagesCss = $s->customPagesCss ?? '';
    }

    public string $previewPage = 'home';

    public function save(): void
    {
        $s = app(ThemeSettings::class);
        $s->customCss      = $this->customCss;
        $s->homeCss        = $this->homeCss;
        $s->toursCss       = $this->toursCss;
        $s->tourDetailCss  = $this->tourDetailCss;
        $s->blogCss        = $this->blogCss;
        $s->contactCss     = $this->contactCss;
        $s->customPagesCss = $this->customPagesCss;
        $s->save();

        $this->dispatch('css-saved');

        Notification::make()->title('CSS saved successfully.')->success()->send();
    }

    public function insertSnippet(string $snippet): void
    {
        $prop = match ($this->activePage) {
            'home'        => 'homeCss',
            'tours'       => 'toursCss',
            'tourDetail'  => 'tourDetailCss',
            'blog'        => 'blogCss',
            'contact'     => 'contactCss',
            'customPages' => 'customPagesCss',
            default       => 'customCss',
        };
        $this->$prop = rtrim($this->$prop) . "\n\n" . $snippet;
    }
}
