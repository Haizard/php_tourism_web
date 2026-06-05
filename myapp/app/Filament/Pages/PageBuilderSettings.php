<?php

namespace App\Filament\Pages;

use App\Models\DetailTemplate;
use App\Models\PageSection;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use App\Filament\Resources\CustomPageResource;

class PageBuilderSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $navigationLabel = 'Detail Page Styles';
    protected static ?string $navigationGroup = 'Page Builder';
    protected static ?int $navigationSort = 3;
    protected static ?string $title = 'Tour & Blog Detail Styles';
    protected static string $view = 'filament.pages.page-builder-settings';
    protected static ?string $slug = 'page-builder-settings';

    public array $tourData = [];
    public array $blogData = [];

    public function mount(): void
    {
        $tour = DetailTemplate::firstOrCreate(['page_type' => 'tour_detail'], [
            'layout' => 'default', 'header_style' => 'default', 'card_style' => 'default',
            'visible_sections' => ['gallery' => true, 'booking_form' => true, 'related_tours' => true, 'itinerary' => true],
            'custom_css' => '',
        ]);
        $blog = DetailTemplate::firstOrCreate(['page_type' => 'blog_detail'], [
            'layout' => 'default', 'header_style' => 'default', 'card_style' => 'default',
            'visible_sections' => ['author_bio' => true, 'related_posts' => true, 'social_share' => true],
            'custom_css' => '',
        ]);

        $this->tourData = array_merge($tour->toArray(), ['visible_sections' => $tour->visible_sections ?? []]);
        $this->blogData = array_merge($blog->toArray(), ['visible_sections' => $blog->visible_sections ?? []]);

        $this->tourForm->fill($this->tourData);
        $this->blogForm->fill($this->blogData);
    }

    protected function getForms(): array
    {
        return ['tourForm', 'blogForm'];
    }

    public function tourForm(Form $form): Form
    {
        return $form->schema($this->getTemplateSchema('tour'))->statePath('tourData');
    }

    public function blogForm(Form $form): Form
    {
        return $form->schema($this->getTemplateSchema('blog'))->statePath('blogData');
    }

    private function getTemplateSchema(string $type): array
    {
        $tourSections = [
            Forms\Components\Toggle::make('visible_sections.gallery')->label('Show Gallery')->default(true),
            Forms\Components\Toggle::make('visible_sections.booking_form')->label('Show Booking Form')->default(true),
            Forms\Components\Toggle::make('visible_sections.related_tours')->label('Show Related Tours')->default(true),
            Forms\Components\Toggle::make('visible_sections.itinerary')->label('Show Itinerary')->default(true),
        ];
        $blogSections = [
            Forms\Components\Toggle::make('visible_sections.author_bio')->label('Show Author Bio')->default(true),
            Forms\Components\Toggle::make('visible_sections.related_posts')->label('Show Related Posts')->default(true),
            Forms\Components\Toggle::make('visible_sections.social_share')->label('Show Social Share')->default(true),
        ];

        return [
            Forms\Components\Section::make('Layout & Style')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Select::make('layout')
                        ->label('Page Layout')
                        ->options(['default' => 'Default (Content + Sidebar)', 'full_width' => 'Full Width', 'centered' => 'Centered Content'])
                        ->default('default'),
                    Forms\Components\Select::make('header_style')
                        ->label('Header / Banner Style')
                        ->options(['default' => 'Full-width Image Banner', 'minimal' => 'Minimal Text Only', 'split' => 'Split (Image + Text)', 'overlay' => 'Dark Overlay'])
                        ->default('default'),
                    Forms\Components\Select::make('card_style')
                        ->label('Card / Info Box Style')
                        ->options(['default' => 'Default', 'bordered' => 'Bordered', 'shadow' => 'Drop Shadow', 'glass' => 'Glassmorphism'])
                        ->default('default'),
                ]),
            ]),
            Forms\Components\Section::make('Visible Sections')->schema($type === 'tour' ? $tourSections : $blogSections)->columns(2),
            Forms\Components\Section::make('Custom CSS')->collapsed()->schema([
                Forms\Components\Textarea::make('custom_css')
                    ->label('Custom CSS')
                    ->rows(8)
                    ->helperText('Applied only to ' . ($type === 'tour' ? 'tour detail' : 'blog post') . ' pages.')
                    ->extraAttributes(['style' => 'font-family: monospace; font-size: 0.85rem;']),
            ]),
        ];
    }

    public function saveTour(): void
    {
        $data = $this->tourForm->getState();
        DetailTemplate::where('page_type', 'tour_detail')->update([
            'layout'           => $data['layout'] ?? 'default',
            'header_style'     => $data['header_style'] ?? 'default',
            'card_style'       => $data['card_style'] ?? 'default',
            'visible_sections' => $data['visible_sections'] ?? [],
            'custom_css'       => $data['custom_css'] ?? '',
        ]);
        Notification::make()->title('Tour detail settings saved.')->success()->send();
    }

    public function saveBlog(): void
    {
        $data = $this->blogForm->getState();
        DetailTemplate::where('page_type', 'blog_detail')->update([
            'layout'           => $data['layout'] ?? 'default',
            'header_style'     => $data['header_style'] ?? 'default',
            'card_style'       => $data['card_style'] ?? 'default',
            'visible_sections' => $data['visible_sections'] ?? [],
            'custom_css'       => $data['custom_css'] ?? '',
        ]);
        Notification::make()->title('Blog detail settings saved.')->success()->send();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
