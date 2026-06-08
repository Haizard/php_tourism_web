<x-filament-panels::page>

    {{-- ── Toolbar ── --}}
    <div class="flex items-center justify-between mb-4">
        <p class="text-sm text-gray-500">
            Write CSS on the left. Click any snippet on the right to insert it — then adjust the values.
        </p>
        <button
            wire:click="save"
            wire:loading.attr="disabled"
            class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-primary-700 active:scale-95 transition disabled:opacity-60"
        >
            <span wire:loading.remove wire:target="save">💾 Save CSS</span>
            <span wire:loading wire:target="save">Saving…</span>
        </button>
    </div>

    <div
        x-data="{
            activeTab: 'snippets',
            insertSnippet(snippet) {
                const ta = document.getElementById('css-textarea');
                if (!ta) return;
                const current = ta.value.trimEnd();
                ta.value = current + (current ? '\n\n' : '') + snippet + '\n';
                ta.dispatchEvent(new Event('input'));
                ta.focus();
                ta.scrollTop = ta.scrollHeight;
                // sync with Livewire
                @this.set('css', ta.value);
            }
        }"
        class="grid gap-6 lg:grid-cols-5"
    >
        {{-- ══════════════════════════════
             LEFT — CSS Editor (3/5)
        ══════════════════════════════ --}}
        <div class="lg:col-span-3 flex flex-col gap-3">

            <div class="rounded-xl border border-gray-200 bg-gray-900 overflow-hidden shadow-inner">
                {{-- editor header --}}
                <div class="flex items-center justify-between px-4 py-2 bg-gray-800 border-b border-gray-700">
                    <span class="text-xs font-mono text-gray-400">custom.css — applies to every public page</span>
                    <button
                        x-on:click="@this.set('css', '')"
                        class="text-xs text-gray-500 hover:text-red-400 transition"
                        onclick="if(!confirm('Clear all custom CSS?')) return false;"
                    >✕ Clear</button>
                </div>

                {{-- textarea --}}
                <textarea
                    id="css-textarea"
                    wire:model.live="css"
                    rows="32"
                    spellcheck="false"
                    placeholder="/* Start typing CSS here, or click a snippet → */

/* Example: change the hero height */
.page-hero { min-height: 560px; }

/* Example: white sticky navbar */
header { background: #ffffff !important; box-shadow: 0 2px 12px rgba(0,0,0,.08); }"
                    class="w-full bg-gray-900 text-green-300 font-mono text-sm px-5 py-4 resize-none outline-none leading-relaxed placeholder:text-gray-600"
                    style="tab-size:2; -moz-tab-size:2;"
                ></textarea>
            </div>

            {{-- live hint --}}
            <p class="text-xs text-gray-400 text-center">
                After saving, reload the public site to see your changes take effect.
            </p>
        </div>

        {{-- ══════════════════════════════
             RIGHT — Snippet Library (2/5)
        ══════════════════════════════ --}}
        <div class="lg:col-span-2 flex flex-col gap-3">

            {{-- Tabs --}}
            <div class="flex rounded-lg border border-gray-200 overflow-hidden text-sm font-medium">
                <button
                    x-on:click="activeTab='snippets'"
                    :class="activeTab==='snippets' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="flex-1 px-3 py-2 transition"
                >✨ Snippets</button>
                <button
                    x-on:click="activeTab='selectors'"
                    :class="activeTab==='selectors' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="flex-1 px-3 py-2 transition"
                >🎯 Selectors</button>
                <button
                    x-on:click="activeTab='variables'"
                    :class="activeTab==='variables' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    class="flex-1 px-3 py-2 transition"
                >🎨 Variables</button>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white overflow-y-auto shadow-sm" style="max-height:680px;">

                {{-- ── TAB: SNIPPETS ── --}}
                <div x-show="activeTab==='snippets'" class="divide-y divide-gray-100">

                    @php
                    $snippetGroups = [
                        '🔝 Navbar' => [
                            ['label' => 'Sticky white navbar',
                             'desc'  => 'Always-white background with shadow',
                             'code'  => "header {\n  background: #ffffff !important;\n  box-shadow: 0 2px 16px rgba(0,0,0,.10);\n}"],
                            ['label' => 'Transparent navbar',
                             'desc'  => 'Fully see-through (works best over hero)',
                             'code'  => "header {\n  background: transparent !important;\n  box-shadow: none;\n}"],
                            ['label' => 'Larger nav links',
                             'desc'  => 'Increase font size of navbar links',
                             'code'  => "header nav a {\n  font-size: 1rem;\n  font-weight: 600;\n}"],
                        ],
                        '🌟 Hero Section' => [
                            ['label' => 'Taller hero',
                             'desc'  => 'Make the hero banner taller',
                             'code'  => ".hero-banner,\n.page-hero {\n  min-height: 620px;\n}"],
                            ['label' => 'Darker hero overlay',
                             'desc'  => 'Make the hero image darker for better text contrast',
                             'code'  => ".hero-banner::before,\n.page-hero::before {\n  background: rgba(0, 0, 0, 0.55) !important;\n}"],
                            ['label' => 'Hero title size',
                             'desc'  => 'Change the large heading in the hero',
                             'code'  => ".hero-banner h1,\n.page-hero h1 {\n  font-size: 3.5rem;\n  font-weight: 900;\n  line-height: 1.1;\n}"],
                            ['label' => 'Center hero text',
                             'desc'  => 'Center-align all hero content',
                             'code'  => ".hero-banner > div,\n.page-hero > div {\n  text-align: center;\n  align-items: center;\n}"],
                        ],
                        '🎴 Tour Cards' => [
                            ['label' => 'Rounded tour cards',
                             'desc'  => 'Increase card border radius',
                             'code'  => ".hero-stack-card {\n  border-radius: 1.5rem;\n}"],
                            ['label' => 'Deeper card shadows',
                             'desc'  => 'More dramatic drop shadow on cards',
                             'code'  => ".hero-stack-card {\n  box-shadow: 0 40px 100px rgba(0,0,0,.25);\n}"],
                            ['label' => 'Card hover lift',
                             'desc'  => 'Cards float up on hover',
                             'code'  => ".hero-stack-card {\n  transition: transform .25s ease, box-shadow .25s ease;\n}\n.hero-stack-card:hover {\n  transform: translateY(-6px);\n  box-shadow: 0 50px 120px rgba(0,0,0,.30);\n}"],
                        ],
                        '🔘 Buttons' => [
                            ['label' => 'Pill-shaped buttons',
                             'desc'  => 'Fully rounded CTA buttons',
                             'code'  => "a[class*=\"rounded-\"], button[class*=\"rounded-\"] {\n  border-radius: 9999px !important;\n}"],
                            ['label' => 'Square buttons',
                             'desc'  => 'Remove rounding from all buttons',
                             'code'  => "a[class*=\"rounded-\"], button[class*=\"rounded-\"] {\n  border-radius: 4px !important;\n}"],
                            ['label' => 'Bigger CTA buttons',
                             'desc'  => 'Larger padding on primary buttons',
                             'code'  => ".btn-primary,\na[class*=\"bg-\"][class*=\"text-white\"] {\n  padding: 1rem 2.5rem;\n  font-size: 1.05rem;\n}"],
                        ],
                        '📝 Typography' => [
                            ['label' => 'Change body font',
                             'desc'  => 'Use a different Google Font (add @import first)',
                             'code'  => "@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');\n\nbody {\n  font-family: 'Poppins', sans-serif;\n}"],
                            ['label' => 'Larger body text',
                             'desc'  => 'Increase base font size',
                             'code'  => "body {\n  font-size: 1.0625rem;\n  line-height: 1.75;\n}"],
                            ['label' => 'Section headings',
                             'desc'  => 'Style all section h2 titles',
                             'code'  => "section h2 {\n  font-size: 2.25rem;\n  font-weight: 800;\n  letter-spacing: -0.02em;\n}"],
                        ],
                        '🦶 Footer' => [
                            ['label' => 'Dark footer',
                             'desc'  => 'Solid dark background on the footer',
                             'code'  => "footer {\n  background: #0f172a !important;\n  color: #e2e8f0;\n}"],
                            ['label' => 'Footer link color',
                             'desc'  => 'Change color of footer links',
                             'code'  => "footer a {\n  color: #94a3b8;\n}\nfooter a:hover {\n  color: #ffffff;\n}"],
                        ],
                        '📄 Tour & Blog Detail Pages' => [
                            ['label' => 'Wider content column',
                             'desc'  => 'Wider main content area on detail pages',
                             'code'  => ".tour-detail-content,\n.blog-detail-content {\n  max-width: 56rem;\n}"],
                            ['label' => 'Price badge color',
                             'desc'  => 'Change the price highlight color',
                             'code'  => ".tour-price {\n  color: #16a34a;\n  font-size: 2rem;\n  font-weight: 900;\n}"],
                            ['label' => 'Prose content style',
                             'desc'  => 'Style the article/tour body text',
                             'code'  => ".prose p {\n  font-size: 1.0625rem;\n  line-height: 1.8;\n  color: #334155;\n}"],
                        ],
                    ];
                    @endphp

                    @foreach($snippetGroups as $groupName => $snippets)
                        <div x-data="{ open: false }">
                            <button
                                x-on:click="open = !open"
                                class="flex items-center justify-between w-full px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition"
                            >
                                <span>{{ $groupName }}</span>
                                <svg x-bind:class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition class="divide-y divide-gray-50">
                                @foreach($snippets as $snippet)
                                    <div class="px-4 py-3 bg-gray-50/50 hover:bg-primary-50/30 transition">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">{{ $snippet['label'] }}</p>
                                                <p class="text-xs text-gray-500 mt-0.5">{{ $snippet['desc'] }}</p>
                                                <pre class="mt-2 text-xs text-gray-600 bg-gray-100 rounded p-2 overflow-x-auto whitespace-pre-wrap font-mono leading-relaxed">{{ $snippet['code'] }}</pre>
                                            </div>
                                            <button
                                                x-on:click="insertSnippet({{ Js::from($snippet['code']) }})"
                                                class="flex-shrink-0 rounded-lg bg-primary-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-primary-700 active:scale-95 transition mt-0.5"
                                            >+ Insert</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ── TAB: SELECTORS ── --}}
                <div x-show="activeTab==='selectors'" class="divide-y divide-gray-100">
                    @php
                    $selectorGroups = [
                        '🔝 Navbar & Header' => [
                            'header'                   => 'The whole top navigation bar',
                            'header nav'               => 'The nav links wrapper',
                            'header nav a'             => 'Individual nav links',
                            'header .logo'             => 'Site logo/name in navbar',
                        ],
                        '🌟 Hero & Banners' => [
                            '.hero-banner'             => 'Homepage hero section',
                            '.page-hero'               => 'Inner page hero (tours, blog, etc.)',
                            '.hero-banner h1'          => 'Hero main headline',
                            '.hero-banner p'           => 'Hero subtitle/description',
                            '.hero-stack-card'         => 'Floating tour preview cards in hero',
                            '.hero-stack-card img'     => 'Images inside hero cards',
                        ],
                        '🏠 Homepage Sections' => [
                            'section'                  => 'Every section block',
                            'section h2'               => 'Section headings',
                            'section p'                => 'Section body text',
                            '#featured-tours'          => 'Featured tours grid section',
                            '#destinations'            => 'Destinations section',
                            '#testimonials'            => 'Testimonials / reviews section',
                            '#gallery'                 => 'Gallery section',
                            '#faq'                     => 'FAQ accordion section',
                        ],
                        '📝 Typography' => [
                            'body'                     => 'Base font for entire site',
                            'h1'                       => 'All h1 headings',
                            'h2'                       => 'All h2 headings',
                            'h3'                       => 'All h3 headings',
                            'p'                        => 'All paragraph text',
                            '.prose p'                 => 'Rich text body (blog/tour content)',
                            '.prose h2'                => 'Headings inside rich text',
                        ],
                        '🎴 Cards & UI' => [
                            '.hero-stack-card'         => 'Hero floating cards',
                            '.page-hero'               => 'Page header banners',
                            'img'                      => 'All images',
                        ],
                        '🦶 Footer' => [
                            'footer'                   => 'Entire footer area',
                            'footer a'                 => 'Footer links',
                            'footer p'                 => 'Footer body text',
                        ],
                    ];
                    @endphp

                    @foreach($selectorGroups as $groupName => $selectors)
                        <div x-data="{ open: false }">
                            <button
                                x-on:click="open = !open"
                                class="flex items-center justify-between w-full px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition"
                            >
                                <span>{{ $groupName }}</span>
                                <svg x-bind:class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition class="divide-y divide-gray-50">
                                @foreach($selectors as $selector => $description)
                                    <div class="flex items-center justify-between gap-3 px-4 py-2.5 bg-gray-50/40 hover:bg-primary-50/30 transition">
                                        <div class="flex-1 min-w-0">
                                            <code class="text-xs font-mono font-bold text-primary-700 bg-primary-50 px-2 py-0.5 rounded">{{ $selector }}</code>
                                            <p class="text-xs text-gray-500 mt-1">{{ $description }}</p>
                                        </div>
                                        <button
                                            x-on:click="insertSnippet('{{ $selector }} {\n  /* your styles here */\n}')"
                                            class="flex-shrink-0 rounded-lg border border-gray-200 bg-white px-3 py-1 text-xs font-medium text-gray-700 hover:bg-primary-600 hover:text-white hover:border-primary-600 active:scale-95 transition"
                                        >+ Insert</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- ── TAB: CSS VARIABLES ── --}}
                <div x-show="activeTab==='variables'" class="p-4 space-y-4">
                    <p class="text-xs text-gray-500 leading-relaxed">
                        These CSS variables are defined globally by the Theme Settings. You can override them here or use them inside your custom CSS rules.
                    </p>

                    @php
                    $variables = [
                        '--color-primary'       => ['label' => 'Primary Color',         'desc' => 'Used for nav links, tags, and accent highlights. Set in Theme Settings.'],
                        '--color-accent'        => ['label' => 'Accent Color',           'desc' => 'Used for buttons, prices, and CTAs. Set in Theme Settings.'],
                        '--color-bg'            => ['label' => 'Background Color',       'desc' => 'Site-wide background color. Set in Theme Settings.'],
                        '--font-family'         => ['label' => 'Font Family',            'desc' => 'Main typeface for the whole site. Set in Theme Settings.'],
                        '--hero-overlay-opacity'=> ['label' => 'Hero Overlay Opacity',   'desc' => 'Controls how dark the hero image overlay is (0–1). Set in Theme Settings.'],
                    ];
                    @endphp

                    <div class="rounded-xl border border-gray-200 overflow-hidden divide-y divide-gray-100">
                        @foreach($variables as $varName => $meta)
                            <div class="p-3 bg-white hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between gap-2">
                                    <code class="text-xs font-mono font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded">{{ $varName }}</code>
                                    <button
                                        x-on:click="insertSnippet(':root {\n  {{ $varName }}: /* your value */;\n}')"
                                        class="text-xs text-gray-500 hover:text-primary-600 transition font-medium"
                                    >Override ↗</button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1.5 font-semibold">{{ $meta['label'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $meta['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="rounded-xl bg-amber-50 border border-amber-200 p-4">
                        <p class="text-xs font-semibold text-amber-800 mb-2">💡 How to use variables in CSS</p>
                        <pre class="text-xs font-mono text-amber-700 leading-relaxed whitespace-pre-wrap">/* Reference a variable */
.my-element {
  color: var(--color-primary);
  background: var(--color-accent);
}

/* Override a variable globally */
:root {
  --color-primary: #2563eb;
}</pre>
                    </div>

                    <div class="rounded-xl bg-blue-50 border border-blue-200 p-4">
                        <p class="text-xs font-semibold text-blue-800 mb-2">🎨 Change colors without raw CSS</p>
                        <p class="text-xs text-blue-700">Go to <strong>Appearance → Theme</strong> to change primary color, accent color, and background color using color pickers instead.</p>
                    </div>
                </div>

            </div>{{-- end snippet panel --}}

            {{-- Keyboard hint --}}
            <p class="text-xs text-gray-400 text-center">
                Tip: After clicking <strong>+ Insert</strong>, scroll up in the editor and edit the inserted values.
            </p>
        </div>
    </div>

</x-filament-panels::page>
