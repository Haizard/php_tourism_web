<?php

namespace App\Filament\Pages;

use App\Models\DetailTemplate;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class PageBuilderSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-paint-brush';
    protected static ?string $navigationLabel = 'Detail Page Styles';
    protected static ?string $navigationGroup = 'Page Builder';
    protected static ?int    $navigationSort  = 3;
    protected static ?string $title           = 'Tour & Blog Detail Styles';
    protected static string  $view            = 'filament.pages.page-builder-settings';
    protected static ?string $slug            = 'page-builder-settings';

    public array $tourData = [];
    public array $blogData = [];

    private static array $tourSectionsDefault = [
        'main'    => [
            ['key' => 'overview',          'label' => 'Tour Overview',     'is_active' => true],
            ['key' => 'itinerary',         'label' => 'Itinerary',         'is_active' => true],
            ['key' => 'included_services', 'label' => 'Included Services', 'is_active' => true],
            ['key' => 'excluded_services', 'label' => 'Excluded Services', 'is_active' => true],
        ],
        'sidebar' => [
            ['key' => 'tour_details_card', 'label' => 'Tour Details Card', 'is_active' => true],
            ['key' => 'booking_form',      'label' => 'Booking Form',      'is_active' => true],
        ],
        'below'   => [
            ['key' => 'related_tours', 'label' => 'Related Tours', 'is_active' => true],
        ],
    ];

    private static array $blogSectionsDefault = [
        'main'    => [
            ['key' => 'content',      'label' => 'Article Content',      'is_active' => true],
            ['key' => 'social_share', 'label' => 'Social Share Buttons', 'is_active' => true],
        ],
        'sidebar' => [
            ['key' => 'article_details', 'label' => 'Article Details', 'is_active' => true],
        ],
        'below'   => [
            ['key' => 'related_posts', 'label' => 'Related Posts', 'is_active' => true],
        ],
    ];

    public function mount(): void
    {
        $tour = DetailTemplate::firstOrCreate(['page_type' => 'tour_detail'], [
            'layout' => 'default', 'header_style' => 'default', 'card_style' => 'default',
            'visible_sections' => ['booking_form' => true, 'related_tours' => true, 'itinerary' => true],
            'sections_config'  => self::$tourSectionsDefault,
            'custom_css' => '',
        ]);
        $blog = DetailTemplate::firstOrCreate(['page_type' => 'blog_detail'], [
            'layout' => 'default', 'header_style' => 'default', 'card_style' => 'default',
            'visible_sections' => ['related_posts' => true, 'social_share' => true],
            'sections_config'  => self::$blogSectionsDefault,
            'custom_css' => '',
        ]);

        $tourArr = $tour->toArray();
        $tourArr['sections_config'] = $tour->sections_config ?? self::$tourSectionsDefault;

        $blogArr = $blog->toArray();
        $blogArr['sections_config'] = $blog->sections_config ?? self::$blogSectionsDefault;

        $this->tourData = $tourArr;
        $this->blogData = $blogArr;

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
        return [
            Section::make('Layout & Style')->schema([
                Grid::make(3)->schema([
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

            Section::make('Section Order & Visibility')
                ->description('Drag ⠿ to reorder. Toggle to show or hide. Changes are applied after saving.')
                ->schema([
                    Section::make('📄 Main Content')
                        ->description($type === 'tour' ? 'Sections shown in the main (left) content column.' : 'Sections shown in the main article content area.')
                        ->collapsible()
                        ->schema([
                            $this->sectionRepeater("sections_config.main"),
                        ]),

                    Section::make('🗂 Sidebar Widgets')
                        ->description('Sections shown in the right sidebar. Hidden automatically when Full Width layout is selected.')
                        ->collapsible()
                        ->schema([
                            $this->sectionRepeater("sections_config.sidebar"),
                        ]),

                    Section::make('⬇ Below the Fold')
                        ->description('Sections shown below the main content and sidebar.')
                        ->collapsible()
                        ->schema([
                            $this->sectionRepeater("sections_config.below"),
                        ]),
                ]),

            Section::make('Custom CSS')->collapsed()->schema([
                Forms\Components\Textarea::make('custom_css')
                    ->label('Custom CSS')
                    ->rows(8)
                    ->helperText('Applied only to ' . ($type === 'tour' ? 'tour detail' : 'blog post') . ' pages.')
                    ->extraAttributes(['style' => 'font-family: monospace; font-size: 0.85rem;']),
            ]),
        ];
    }

    private function sectionRepeater(string $statePath): Repeater
    {
        return Repeater::make($statePath)
            ->label('')
            ->schema([
                Hidden::make('key'),
                Hidden::make('label'),
                Grid::make(2)->schema([
                    Placeholder::make('section_name')
                        ->label('')
                        ->content(fn (Get $get): string =>
                            $get('label') ?: ucwords(str_replace('_', ' ', $get('key') ?: 'Section'))
                        ),
                    Toggle::make('is_active')
                        ->label('Visible')
                        ->default(true)
                        ->inline(true),
                ]),
            ])
            ->reorderable()
            ->reorderableWithDragAndDrop()
            ->addable(false)
            ->deletable(false)
            ->itemLabel(fn (array $state): string =>
                (($state['is_active'] ?? true) ? '👁  ' : '🚫  ') .
                ($state['label'] ?? ucwords(str_replace('_', ' ', $state['key'] ?? 'Section')))
            )
            ->collapsible()
            ->collapsed();
    }

    public function saveTour(): void
    {
        $data = $this->tourForm->getState();
        DetailTemplate::where('page_type', 'tour_detail')->update([
            'layout'          => $data['layout']         ?? 'default',
            'header_style'    => $data['header_style']   ?? 'default',
            'card_style'      => $data['card_style']     ?? 'default',
            'sections_config' => $data['sections_config'] ?? self::$tourSectionsDefault,
            'custom_css'      => $data['custom_css']     ?? '',
        ]);
        Notification::make()->title('Tour detail settings saved.')->success()->send();
    }

    public function saveBlog(): void
    {
        $data = $this->blogForm->getState();
        DetailTemplate::where('page_type', 'blog_detail')->update([
            'layout'          => $data['layout']         ?? 'default',
            'header_style'    => $data['header_style']   ?? 'default',
            'card_style'      => $data['card_style']     ?? 'default',
            'sections_config' => $data['sections_config'] ?? self::$blogSectionsDefault,
            'custom_css'      => $data['custom_css']     ?? '',
        ]);
        Notification::make()->title('Blog detail settings saved.')->success()->send();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
