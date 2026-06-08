<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomPageResource\Pages;
use App\Models\CustomPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CustomPageResource extends Resource
{
    protected static ?string $model = CustomPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Pages';
    protected static ?string $navigationGroup = 'Page Builder';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('page_tabs')->tabs([

                Forms\Components\Tabs\Tab::make('Page Settings')->icon('heroicon-o-cog-6-tooth')->schema([
                    Forms\Components\Section::make()->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Page Title')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                                    $set('slug', Str::slug($state))),
                            Forms\Components\TextInput::make('slug')
                                ->label('URL Slug')
                                ->required()
                                ->maxLength(255)
                                ->prefix('/{locale}/')
                                ->unique(CustomPage::class, 'slug', ignoreRecord: true)
                                ->helperText('Auto-generated from title. e.g. "about-us" → /{locale}/about-us'),
                        ]),
                        Forms\Components\Toggle::make('is_published')
                            ->label('Published')
                            ->default(false),
                    ]),
                    Forms\Components\Section::make('SEO')->collapsed()->schema([
                        Forms\Components\TextInput::make('seo_meta_title')->label('Meta Title')->maxLength(70),
                        Forms\Components\Textarea::make('seo_meta_description')->label('Meta Description')->rows(3)->maxLength(160),
                        Forms\Components\TextInput::make('seo_keywords')->label('Keywords')->maxLength(255),
                    ]),
                    Forms\Components\Section::make('Page-level Custom CSS')->collapsed()->schema([
                        Forms\Components\Textarea::make('custom_css')
                            ->label('Custom CSS')
                            ->rows(8)
                            ->helperText('CSS applied to this entire page. Wrap in selectors to target specific elements.')
                            ->extraAttributes(['style' => 'font-family: monospace; font-size: 0.85rem;']),
                    ]),
                ]),

                Forms\Components\Tabs\Tab::make('Section Builder')->icon('heroicon-o-squares-2x2')->schema([
                    Forms\Components\Repeater::make('sections')
                        ->relationship('sections')
                        ->schema(self::getSectionBuilderSchema())
                        ->orderColumn('sort_order')
                        ->reorderable()
                        ->collapsible()
                        ->collapsed()
                        ->addActionLabel('+ Add Section')
                        ->itemLabel(fn (array $state): ?string =>
                            ($state['section_type'] ? '['.strtoupper(str_replace('_', ' ', $state['section_type'])).']' : '') .
                            ' ' . ($state['content']['title'] ?? $state['content']['html_content'] ?? 'Section')
                        )
                        ->cloneable()
                        ->grid(1),
                ]),

            ])->columnSpanFull(),
        ]);
    }

    public static function getSectionBuilderSchema(): array
    {
        return [
            Forms\Components\Grid::make(3)->schema([
                Forms\Components\Select::make('section_type')
                    ->label('Section Type')
                    ->options([
                        'hero'        => '🖼 Hero Banner',
                        'text_block'  => '📝 Text Block',
                        'image_text'  => '🖼 Image + Text',
                        'cards_grid'  => '🃏 Cards Grid',
                        'stats'       => '📊 Stats / Numbers',
                        'cta'         => '🎯 Call to Action',
                        'gallery_block' => '📸 Gallery',
                        'html_block'  => '🧩 Raw HTML',
                        'tours_list'  => '🗺 Tours Listing',
                        'blogs_list'  => '📰 Blogs Listing',
                    ])
                    ->required()
                    ->live()
                    ->columnSpan(2),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->columnSpan(1),
            ]),

            // ── HERO ────────────────────────────────────────────────────────
            Forms\Components\Fieldset::make('Hero Content')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'hero')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('content.title')->label('Headline'),
                        Forms\Components\TextInput::make('content.subtitle')->label('Sub-headline'),
                        Forms\Components\TextInput::make('content.badge_text')->label('Badge Text'),
                        Forms\Components\Select::make('content.bg_type')->label('Background')->options(['none' => 'None', 'color' => 'Color', 'image' => 'Image'])->live()->default('none'),
                        Forms\Components\ColorPicker::make('content.bg_color')->label('BG Color')->visible(fn (Forms\Get $get) => $get('content.bg_type') === 'color'),
                        Forms\Components\FileUpload::make('content.bg_image')->label('BG Image')->image()->disk('public')->directory('page-builder')->visible(fn (Forms\Get $get) => $get('content.bg_type') === 'image'),
                        Forms\Components\Select::make('content.text_color')
                            ->label('Text Color')
                            ->options(['dark' => 'Dark', 'light' => 'Light'])
                            ->default('dark')
                            ->visible(fn (Forms\Get $get) => $get('content.bg_type') !== 'none'),
                        Forms\Components\TextInput::make('content.overlay_opacity')->label('Overlay Opacity')->placeholder('0.6')->visible(fn (Forms\Get $get) => $get('content.bg_type') === 'image'),
                        Forms\Components\TextInput::make('content.primary_btn_text')->label('Primary Button Text'),
                        Forms\Components\TextInput::make('content.primary_btn_url')->label('Primary Button URL'),
                        Forms\Components\TextInput::make('content.secondary_btn_text')->label('Secondary Button Text'),
                        Forms\Components\TextInput::make('content.secondary_btn_url')->label('Secondary Button URL'),
                    ]),
                ]),

            // ── TEXT BLOCK ──────────────────────────────────────────────────
            Forms\Components\Fieldset::make('Text Content')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'text_block')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('content.title')->label('Title'),
                        Forms\Components\Select::make('content.alignment')->label('Alignment')->options(['left' => 'Left', 'center' => 'Center', 'right' => 'Right'])->default('left'),
                        Forms\Components\Select::make('content.bg_type')->label('Background')->options(['none' => 'None', 'color' => 'Color', 'image' => 'Image'])->live()->default('none'),
                        Forms\Components\ColorPicker::make('content.bg_color')->label('BG Color')->visible(fn (Forms\Get $get) => $get('content.bg_type') === 'color'),
                        Forms\Components\Select::make('content.text_color')
                            ->label('Text Color')
                            ->options(['dark' => 'Dark', 'light' => 'Light'])
                            ->default('dark')
                            ->visible(fn (Forms\Get $get) => $get('content.bg_type') !== 'none'),
                        Forms\Components\FileUpload::make('content.bg_image')->label('BG Image')->image()->disk('public')->directory('page-builder')->visible(fn (Forms\Get $get) => $get('content.bg_type') === 'image'),
                    ]),
                    Forms\Components\RichEditor::make('content.body')->label('Content')->columnSpanFull(),
                ]),

            // ── IMAGE + TEXT ─────────────────────────────────────────────────
            Forms\Components\Fieldset::make('Image + Text Content')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'image_text')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('content.title')->label('Title'),
                        Forms\Components\Select::make('content.image_position')->label('Image Position')->options(['left' => 'Image Left', 'right' => 'Image Right'])->default('left'),
                        Forms\Components\FileUpload::make('content.image')->label('Image')->image()->disk('public')->directory('page-builder'),
                        Forms\Components\Select::make('content.bg_type')->label('Background')->options(['none' => 'None', 'color' => 'Color'])->live()->default('none'),
                        Forms\Components\ColorPicker::make('content.bg_color')->label('BG Color')->visible(fn (Forms\Get $get) => $get('content.bg_type') === 'color'),
                        Forms\Components\Select::make('content.text_color')
                            ->label('Text Color')
                            ->options(['dark' => 'Dark', 'light' => 'Light'])
                            ->default('dark')
                            ->visible(fn (Forms\Get $get) => $get('content.bg_type') !== 'none'),
                        Forms\Components\TextInput::make('content.btn_text')->label('Button Text'),
                        Forms\Components\TextInput::make('content.btn_url')->label('Button URL'),
                    ]),
                    Forms\Components\RichEditor::make('content.body')->label('Content')->columnSpanFull(),
                ]),

            // ── CARDS GRID ──────────────────────────────────────────────────
            Forms\Components\Fieldset::make('Cards Grid Content')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'cards_grid')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('content.title')->label('Section Title'),
                        Forms\Components\TextInput::make('content.subtitle')->label('Subtitle'),
                        Forms\Components\Select::make('content.columns')->label('Columns')->options(['2' => '2 Columns', '3' => '3 Columns', '4' => '4 Columns'])->default('3'),
                        Forms\Components\Select::make('content.bg_type')->label('Background')->options(['none' => 'None', 'color' => 'Color', 'image' => 'Image'])->live()->default('none'),
                        Forms\Components\ColorPicker::make('content.bg_color')->label('BG Color')->visible(fn (Forms\Get $get) => $get('content.bg_type') === 'color'),
                        Forms\Components\FileUpload::make('content.bg_image')->label('BG Image')->image()->disk('public')->directory('page-builder')->visible(fn (Forms\Get $get) => $get('content.bg_type') === 'image'),
                        Forms\Components\Select::make('content.text_color')
                            ->label('Text Color')
                            ->options(['dark' => 'Dark', 'light' => 'Light'])
                            ->default('dark')
                            ->visible(fn (Forms\Get $get) => $get('content.bg_type') !== 'none'),
                        Forms\Components\Select::make('content.card_style')->label('Card Style')->options(['default' => 'Default', 'bordered' => 'Bordered', 'shadow' => 'Shadow', 'glass' => 'Glass'])->default('default'),
                    ]),
                    Forms\Components\Repeater::make('content.cards')
                        ->label('Cards')
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('title')->label('Card Title'),
                                Forms\Components\TextInput::make('badge')->label('Badge Text'),
                                Forms\Components\Textarea::make('description')->label('Description')->rows(2),
                                Forms\Components\TextInput::make('link')->label('Link URL'),
                                Forms\Components\FileUpload::make('image')->label('Card Image')->image()->disk('public')->directory('page-builder'),
                                Forms\Components\TextInput::make('btn_text')->label('Button Text')->default('Learn More'),
                            ]),
                        ])
                        ->addActionLabel('+ Add Card')
                        ->collapsible()
                        ->collapsed()
                        ->itemLabel(fn (array $state) => $state['title'] ?? 'Card')
                        ->columnSpanFull(),
                ]),

            // ── STATS ────────────────────────────────────────────────────────
            Forms\Components\Fieldset::make('Stats Content')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'stats')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('content.title')->label('Section Title'),
                        Forms\Components\TextInput::make('content.subtitle')->label('Subtitle'),
                        Forms\Components\Select::make('content.bg_type')->label('Background')->options(['none' => 'None', 'color' => 'Color', 'image' => 'Image'])->live()->default('none'),
                        Forms\Components\ColorPicker::make('content.bg_color')->label('BG Color')->visible(fn (Forms\Get $get) => $get('content.bg_type') === 'color'),
                        Forms\Components\Select::make('content.text_color')
                            ->label('Text Color')
                            ->options(['dark' => 'Dark', 'light' => 'Light'])
                            ->default('dark')
                            ->visible(fn (Forms\Get $get) => $get('content.bg_type') !== 'none'),
                        Forms\Components\FileUpload::make('content.bg_image')->label('BG Image')->image()->disk('public')->directory('page-builder')->visible(fn (Forms\Get $get) => $get('content.bg_type') === 'image'),
                    ]),
                    Forms\Components\Repeater::make('content.items')
                        ->label('Stats Items')
                        ->schema([
                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\TextInput::make('number')->label('Number / Value')->placeholder('1,200+'),
                                Forms\Components\TextInput::make('label')->label('Label')->placeholder('Happy Clients'),
                                Forms\Components\TextInput::make('icon')->label('Icon (Heroicon name)')->placeholder('heroicon-o-users'),
                            ]),
                        ])
                        ->addActionLabel('+ Add Stat')
                        ->columns(1)
                        ->columnSpanFull(),
                ]),

            // ── CTA ──────────────────────────────────────────────────────────
            Forms\Components\Fieldset::make('Call to Action Content')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'cta')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('content.title')->label('Title'),
                        Forms\Components\TextInput::make('content.subtitle')->label('Subtitle'),
                        Forms\Components\TextInput::make('content.btn_text')->label('Primary Button'),
                        Forms\Components\TextInput::make('content.btn_url')->label('Primary Button URL'),
                        Forms\Components\TextInput::make('content.secondary_btn_text')->label('Secondary Button'),
                        Forms\Components\TextInput::make('content.secondary_btn_url')->label('Secondary Button URL'),
                        Forms\Components\Select::make('content.bg_type')->label('Background')->options(['none' => 'None', 'color' => 'Color', 'image' => 'Image'])->live()->default('color'),
                        Forms\Components\ColorPicker::make('content.bg_color')->label('BG Color')->visible(fn (Forms\Get $get) => $get('content.bg_type') === 'color'),
                        Forms\Components\Select::make('content.text_color')
                            ->label('Text Color')
                            ->options(['dark' => 'Dark', 'light' => 'Light'])
                            ->default('dark')
                            ->visible(fn (Forms\Get $get) => $get('content.bg_type') !== 'none'),
                        Forms\Components\FileUpload::make('content.bg_image')->label('BG Image')->image()->disk('public')->directory('page-builder')->visible(fn (Forms\Get $get) => $get('content.bg_type') === 'image'),
                    ]),
                ]),

            // ── GALLERY ──────────────────────────────────────────────────────
            Forms\Components\Fieldset::make('Gallery Content')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'gallery_block')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('content.title')->label('Title'),
                        Forms\Components\TextInput::make('content.subtitle')->label('Subtitle'),
                        Forms\Components\Select::make('content.columns')->label('Columns')->options(['2' => '2', '3' => '3', '4' => '4'])->default('3'),
                        Forms\Components\Select::make('content.bg_type')->label('Background')->options(['none' => 'None', 'color' => 'Color'])->live()->default('none'),
                        Forms\Components\ColorPicker::make('content.bg_color')->label('BG Color')->visible(fn (Forms\Get $get) => $get('content.bg_type') === 'color'),
                        Forms\Components\Select::make('content.text_color')
                            ->label('Text Color')
                            ->options(['dark' => 'Dark', 'light' => 'Light'])
                            ->default('dark')
                            ->visible(fn (Forms\Get $get) => $get('content.bg_type') !== 'none'),
                    ]),
                    Forms\Components\Repeater::make('content.images')
                        ->label('Images')
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\FileUpload::make('image')->label('Image')->image()->disk('public')->directory('page-builder'),
                                Forms\Components\TextInput::make('caption')->label('Caption'),
                            ]),
                        ])
                        ->addActionLabel('+ Add Image')
                        ->columns(1)
                        ->columnSpanFull(),
                ]),

            // ── HTML BLOCK ───────────────────────────────────────────────────
            Forms\Components\Fieldset::make('HTML Block Content')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'html_block')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('content.bg_type')->label('Background')->options(['none' => 'None', 'color' => 'Color'])->live()->default('none'),
                        Forms\Components\ColorPicker::make('content.bg_color')->label('BG Color')->visible(fn (Forms\Get $get) => $get('content.bg_type') === 'color'),
                        Forms\Components\Select::make('content.text_color')
                            ->label('Text Color')
                            ->options(['dark' => 'Dark', 'light' => 'Light'])
                            ->default('dark')
                            ->visible(fn (Forms\Get $get) => $get('content.bg_type') !== 'none'),
                    ]),
                    Forms\Components\Textarea::make('content.html_content')
                        ->label('HTML Content')
                        ->rows(12)
                        ->helperText('Raw HTML — supports inline styles, embeds, and anything your browser can render.')
                        ->extraAttributes(['style' => 'font-family: monospace; font-size: 0.85rem;'])
                        ->columnSpanFull(),
                ]),

            // ── TOURS LIST ───────────────────────────────────────────────────
            Forms\Components\Fieldset::make('Tours List Settings')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'tours_list')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('content.title')->label('Section Title'),
                        Forms\Components\TextInput::make('content.subtitle')->label('Subtitle'),
                        Forms\Components\Select::make('content.limit')->label('Number of Tours')->options(['3' => '3', '6' => '6', '9' => '9', '12' => '12'])->default('6'),
                        Forms\Components\Select::make('content.bg_type')->label('Background')->options(['none' => 'None', 'color' => 'Color'])->live()->default('none'),
                        Forms\Components\ColorPicker::make('content.bg_color')->label('BG Color')->visible(fn (Forms\Get $get) => $get('content.bg_type') === 'color'),
                        Forms\Components\Select::make('content.text_color')
                            ->label('Text Color')
                            ->options(['dark' => 'Dark', 'light' => 'Light'])
                            ->default('dark')
                            ->visible(fn (Forms\Get $get) => $get('content.bg_type') !== 'none'),
                        Forms\Components\Select::make('content.card_style')->label('Card Style')->options(['default' => 'Default', 'bordered' => 'Bordered', 'minimal' => 'Minimal'])->default('default'),
                    ]),
                ]),

            // ── BLOGS LIST ───────────────────────────────────────────────────
            Forms\Components\Fieldset::make('Blogs List Settings')
                ->visible(fn (Forms\Get $get) => $get('section_type') === 'blogs_list')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('content.title')->label('Section Title'),
                        Forms\Components\TextInput::make('content.subtitle')->label('Subtitle'),
                        Forms\Components\Select::make('content.limit')->label('Number of Posts')->options(['3' => '3', '6' => '6', '9' => '9'])->default('6'),
                        Forms\Components\Select::make('content.bg_type')->label('Background')->options(['none' => 'None', 'color' => 'Color'])->live()->default('none'),
                        Forms\Components\ColorPicker::make('content.bg_color')->label('BG Color')->visible(fn (Forms\Get $get) => $get('content.bg_type') === 'color'),
                        Forms\Components\Select::make('content.text_color')
                            ->label('Text Color')
                            ->options(['dark' => 'Dark', 'light' => 'Light'])
                            ->default('dark')
                            ->visible(fn (Forms\Get $get) => $get('content.bg_type') !== 'none'),
                    ]),
                ]),

            // ── SECTION-LEVEL CUSTOM CSS ─────────────────────────────────────
            Forms\Components\Section::make('Section CSS')->collapsed()->schema([
                Forms\Components\Textarea::make('custom_css')
                    ->label('Custom CSS for this section')
                    ->rows(5)
                    ->helperText('Use #pb-section-{id} to scope styles to this section only. Applied inside a <style> tag.')
                    ->extraAttributes(['style' => 'font-family: monospace; font-size: 0.85rem;']),
            ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->badge()->color('gray'),
                Tables\Columns\IconColumn::make('is_published')->boolean()->label('Published'),
                Tables\Columns\TextColumn::make('sections_count')
                    ->label('Sections')
                    ->counts('sections')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('updated_at')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')->label('Published'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (CustomPage $record): string => url('/en/'.$record->slug))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCustomPages::route('/'),
            'create' => Pages\CreateCustomPage::route('/create'),
            'edit'   => Pages\EditCustomPage::route('/{record}/edit'),
        ];
    }
}
