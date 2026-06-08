<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Blog Posts';
    protected static ?string $pluralModelLabel = 'Blog Posts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->description('Enter blog details, then click "✨ Generate with AI" button to auto-fill content')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->reactive()
                            ->debounce(300)
                            ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state)))
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->options(\App\Models\BlogCategory::where('is_active', true)->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->helperText('Select a category for better AI-generated content'),
                        Forms\Components\Textarea::make('excerpt')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Content')
                    ->description('Use AI to generate all content fields at once')
                    ->headerActions([
                        Forms\Components\Actions\Action::make('generate_ai_content')
                            ->label('✨ Generate All Content with AI')
                            ->color('warning')
                            ->icon('heroicon-o-sparkles')
                            ->requiresConfirmation()
                            ->modalHeading('Generate Blog Content with AI')
                            ->modalDescription('AI will generate content based on the title and category you\'ve entered. This will auto-fill the excerpt, content, highlights, and SEO fields.')
                            ->modalSubmitActionLabel('Generate')
                            ->modalCancelActionLabel('Cancel')
                            ->action(function (\Filament\Forms\Get $get, \Filament\Forms\Set $set) {
                                $service = app(\App\Services\GeminiContentService::class);
                                
                                if (!$service->isAvailable()) {
                                    throw new \Exception('Gemini API key is not configured. Please add GEMINI_API_KEY to your .env file.');
                                }

                                // Get form data using $get
                                $title = $get('title');
                                if (empty($title)) {
                                    throw new \Exception('Please enter a blog title before generating content.');
                                }

                                $categoryId = $get('category_id');
                                
                                $categoryName = null;
                                if ($categoryId) {
                                    $category = \App\Models\BlogCategory::find($categoryId);
                                    $categoryName = $category->name ?? null;
                                }

                                $generatedContent = $service->generateCompleteBlog(
                                    $title,
                                    $categoryName
                                );

                                // Update form state with generated content
                                $set('excerpt', $generatedContent['excerpt'] ?? $get('excerpt'));
                                $set('content', $generatedContent['content'] ?? $get('content'));
                                $set('highlights', $generatedContent['highlights'] ?? $get('highlights'));
                                $set('seo_meta_title', $generatedContent['seo_meta_title'] ?? $get('seo_meta_title'));
                                $set('seo_meta_description', $generatedContent['seo_meta_description'] ?? $get('seo_meta_description'));
                                $set('seo_keywords', $generatedContent['seo_keywords'] ?? $get('seo_keywords'));

                                \Filament\Notifications\Notification::make()
                                    ->title('Content generated successfully!')
                                    ->body('Review and edit the AI-generated content before publishing.')
                                    ->success()
                                    ->send();
                            }),
                    ])
                    ->schema([
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->helperText('Click "Generate All Content with AI" to auto-fill this field')
                            ->columnSpanFull(),
                        Forms\Components\KeyValue::make('highlights')
                            ->label('Key Highlights')
                            ->keyLabel('Highlight')
                            ->valueLabel('Description')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->image()
                            ->directory('blog-images')
                            ->disk('public')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('seo_meta_title')
                            ->placeholder('Meta title for search engines')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('seo_meta_description')
                            ->rows(3)
                            ->placeholder('Meta description for search engines')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('seo_keywords')
                            ->placeholder('Comma-separated keywords')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Publishing')
                    ->schema([
                        Forms\Components\Toggle::make('is_published')
                            ->label('Published'),
                        Forms\Components\DatePicker::make('published_at'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_published')
                    ->label('Published')
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'view' => Pages\ViewBlog::route('/{record}'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
