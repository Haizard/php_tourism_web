<x-filament-panels::page>

@php
$previewPages = [
    'home'       => ['label' => '🏠 Home',         'url' => url('/en')],
    'tours'      => ['label' => '🗺️ Tours List',   'url' => url('/en/tours')],
    'blog'       => ['label' => '📰 Blog',          'url' => url('/en/blogs')],
    'contact'    => ['label' => '📬 Contact',        'url' => url('/en/contact')],
    'custom'     => ['label' => '📄 Custom Page',   'url' => url('/en')],
];
$firstTourSlug = \App\Models\Tour::where('is_published', true)->value('slug');
if ($firstTourSlug) {
    $previewPages['tourDetail'] = ['label' => '🎫 Tour Detail', 'url' => url("/en/tours/{$firstTourSlug}")];
}
@endphp

{{-- ── Toolbar ── --}}
<div
    x-data="{
        showPreview: false,
        previewSrc: '{{ $previewPages['home']['url'] }}',
        previewLoading: false,
        previewKey: 0,
        pages: @js($previewPages),
        selectedPage: 'home',
        pickMode: false,
        pickedSelector: '',
        pickedTag: '',
        pickedClasses: [],

        selectPage(key) {
            this.selectedPage = key;
            this.previewSrc = this.pages[key]?.url ?? this.previewSrc;
            this.reloadPreview();
        },
        reloadPreview() {
            this.previewLoading = true;
            this.previewKey++;
        },
        onIframeLoad() {
            this.previewLoading = false;
        },
        iframeSrc() {
            return this.previewSrc + '?_preview=' + this.previewKey + (this.pickMode ? '&_inspect=1' : '');
        },
        togglePickMode() {
            this.pickMode = !this.pickMode;
            this.pickedSelector = '';
            this.reloadPreview();
        },
        insertPicked() {
            if (!this.pickedSelector) return;
            const snippet = this.pickedSelector + ' {\n  /* your styles here */\n}';
            window.dispatchEvent(new CustomEvent('css-editor-insert', { detail: { snippet } }));
            this.pickedSelector = '';
            this.pickMode = false;
            this.reloadPreview();
        },
        dismissPick() {
            this.pickedSelector = '';
            this.pickedTag = '';
            this.pickedClasses = [];
        },
    }"
    x-init="
        window.addEventListener('message', (e) => {
            if (!e.data) return;
            if (e.data.type === 'css-inspector-pick') {
                pickMode = false;
                pickedSelector = e.data.selector || '';
                pickedTag = e.data.tag || '';
                pickedClasses = e.data.classes || [];
                reloadPreview();
            }
            if (e.data.type === 'css-inspector-ready') {
                previewLoading = false;
            }
        });
    "
    @css-saved.window="if (showPreview) { reloadPreview(); }"
    class="space-y-4"
>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-gray-500">
            Write CSS for any page or element. Choose a scope below, pick from the reference panel, click <strong>+ Insert</strong>, then <strong>Save</strong>.
        </p>
        <div class="flex items-center gap-2">
            {{-- Preview toggle --}}
            <button
                x-on:click="showPreview = !showPreview"
                :class="showPreview ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'"
                class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold shadow-sm transition active:scale-95"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <span x-text="showPreview ? 'Hide Preview' : 'Live Preview'"></span>
            </button>
            {{-- Save button --}}
            <button
                wire:click="save"
                wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-primary-700 active:scale-95 transition disabled:opacity-60"
            >
                <span wire:loading.remove wire:target="save">💾 Save All CSS</span>
                <span wire:loading wire:target="save">Saving…</span>
            </button>
        </div>
    </div>

    {{-- ── Live Preview Panel ── --}}
    <div
        x-show="showPreview"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="rounded-xl border border-indigo-200 bg-indigo-50 overflow-hidden shadow-lg"
    >
        {{-- Preview toolbar --}}
        <div class="flex flex-wrap items-center gap-3 px-4 py-2.5 bg-indigo-900 border-b border-indigo-700">
            <span class="text-xs font-bold text-indigo-200 flex items-center gap-1.5">
                <span class="h-2 w-2 rounded-full bg-green-400 animate-pulse"></span>
                Live Preview
            </span>

            {{-- Page picker --}}
            <div class="flex flex-wrap gap-1.5 flex-1">
                @foreach($previewPages as $key => $page)
                    <button
                        x-on:click="selectPage('{{ $key }}')"
                        :class="selectedPage === '{{ $key }}' ? 'bg-indigo-500 text-white' : 'bg-indigo-800/60 text-indigo-200 hover:bg-indigo-700'"
                        class="px-3 py-1 rounded-full text-[10px] font-semibold transition"
                    >{{ $page['label'] }}</button>
                @endforeach
            </div>

            {{-- Controls --}}
            <div class="flex items-center gap-2 ml-auto flex-wrap">
                <span x-show="previewLoading" class="text-[10px] text-indigo-300 animate-pulse">Loading…</span>

                {{-- Pick Element button --}}
                <button
                    x-on:click="togglePickMode()"
                    :class="pickMode
                        ? 'bg-amber-400 text-amber-950 ring-2 ring-amber-300 ring-offset-1 ring-offset-indigo-900'
                        : 'bg-indigo-700 hover:bg-indigo-600 text-indigo-200'"
                    class="rounded-lg px-3 py-1.5 text-[10px] font-bold transition flex items-center gap-1.5"
                    :title="pickMode ? 'Click any element in the preview to pick it — or click again to cancel' : 'Activate element picker: click any visible element to get its CSS selector'"
                >
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/>
                    </svg>
                    <span x-text="pickMode ? '🎯 Click an element…' : '🔍 Pick Element'"></span>
                </button>

                <button
                    x-on:click="reloadPreview()"
                    class="rounded-lg bg-indigo-700 hover:bg-indigo-600 text-indigo-200 px-3 py-1.5 text-[10px] font-semibold transition flex items-center gap-1"
                    title="Reload preview"
                >
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh
                </button>
                <a
                    :href="previewSrc"
                    target="_blank"
                    class="rounded-lg bg-indigo-700 hover:bg-indigo-600 text-indigo-200 px-3 py-1.5 text-[10px] font-semibold transition flex items-center gap-1"
                    title="Open in new tab"
                >
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Open
                </a>
            </div>
        </div>

        {{-- URL bar --}}
        <div class="flex items-center gap-2 px-4 py-1.5 bg-indigo-800 border-b border-indigo-700">
            <svg class="w-3 h-3 text-green-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            <span x-text="previewSrc" class="text-[10px] font-mono text-indigo-300 truncate"></span>
        </div>

        {{-- ── Picked element result bar ── --}}
        <div
            x-show="pickedSelector"
            x-transition
            class="flex items-center gap-3 px-4 py-3 bg-emerald-900 border-b border-emerald-700"
        >
            <span class="flex-shrink-0 h-5 w-5 rounded-full bg-emerald-400 flex items-center justify-center text-emerald-900 text-[10px] font-black">✓</span>
            <div class="flex-1 min-w-0">
                <p class="text-[10px] text-emerald-300 font-semibold uppercase tracking-wider mb-0.5">Element picked</p>
                <div class="flex items-center gap-2 flex-wrap">
                    <code class="text-sm font-mono font-bold text-emerald-200 bg-emerald-950/60 px-2 py-0.5 rounded" x-text="pickedSelector"></code>
                    <template x-if="pickedTag">
                        <span class="text-[10px] text-emerald-400 font-mono" x-text="'<' + pickedTag + '>'"></span>
                    </template>
                </div>
                <div class="flex flex-wrap gap-1 mt-1" x-show="pickedClasses.length">
                    <template x-for="cls in pickedClasses.slice(0,5)" :key="cls">
                        <span class="text-[9px] font-mono bg-emerald-800/50 text-emerald-300 px-1.5 py-0.5 rounded" x-text="'.' + cls"></span>
                    </template>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <button
                    x-on:click="insertPicked()"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-400 hover:bg-emerald-300 text-emerald-950 px-3 py-1.5 text-xs font-bold transition active:scale-95"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Insert into Editor
                </button>
                <button
                    x-on:click="dismissPick()"
                    class="text-emerald-500 hover:text-white text-xs font-semibold transition px-2 py-1"
                    title="Dismiss"
                >✕</button>
            </div>
        </div>

        {{-- iframe --}}
        <div class="relative bg-white" style="height: 580px;">
            {{-- Loading spinner overlay --}}
            <div
                x-show="previewLoading"
                class="absolute inset-0 z-10 flex items-center justify-center bg-white/80 backdrop-blur-sm"
            >
                <div class="flex flex-col items-center gap-3">
                    <svg class="animate-spin h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <p class="text-sm font-semibold text-indigo-600" x-text="pickMode ? 'Activating inspector…' : 'Loading preview…'"></p>
                </div>
            </div>

            {{-- Pick-mode crosshair banner overlay (before iframe loads) --}}
            <div
                x-show="pickMode && !previewLoading"
                class="absolute top-3 left-1/2 -translate-x-1/2 z-20 pointer-events-none"
            >
                <div class="flex items-center gap-2 bg-amber-400 text-amber-950 rounded-full px-4 py-1.5 text-xs font-bold shadow-lg">
                    <span class="animate-ping h-2 w-2 rounded-full bg-amber-700 opacity-75"></span>
                    Hover over an element and click to pick it
                </div>
            </div>

            <iframe
                id="css-preview-iframe"
                :key="previewKey"
                :src="iframeSrc()"
                x-on:load="onIframeLoad()"
                class="w-full h-full border-0"
                :class="pickMode ? 'cursor-crosshair' : ''"
                title="Site Preview"
                sandbox="allow-same-origin allow-scripts allow-forms"
            ></iframe>
        </div>

        {{-- Footer hint --}}
        <div class="px-4 py-2 bg-indigo-900 text-center">
            <p class="text-[10px] text-indigo-300">
                <strong class="text-indigo-200">Save</strong> = preview auto-refreshes ·
                <strong class="text-amber-300">🔍 Pick Element</strong> = click any element to get its CSS selector instantly
            </p>
        </div>
    </div>

<div
    x-data="{
        activeTab: 'snippets',

        insertSnippet(snippet) {
            const taId = 'css-ta-' + $wire.activePage;
            const ta = document.getElementById(taId);
            if (!ta) return;
            const current = ta.value.trimEnd();
            const newVal = current + (current ? '\n\n' : '') + snippet + '\n';
            ta.value = newVal;
            ta.dispatchEvent(new Event('input'));
            ta.focus();
            ta.scrollTop = ta.scrollHeight;
            const prop = {
                global: 'customCss',
                home:   'homeCss',
                tours:  'toursCss',
                tourDetail: 'tourDetailCss',
                blog:   'blogCss',
                contact:'contactCss',
                customPages: 'customPagesCss',
            }[$wire.activePage] || 'customCss';
            $wire.set(prop, newVal);
        }
    }"
    x-init="
        window.addEventListener('css-editor-insert', (e) => {
            if (e.detail?.snippet) insertSnippet(e.detail.snippet);
        });
    "
    class="grid gap-6 lg:grid-cols-5"
>

    {{-- ══════════════════════════════════════
         LEFT — Page Scopes + Code Editor (3/5)
    ══════════════════════════════════════ --}}
    <div class="lg:col-span-3 flex flex-col gap-3">

        {{-- Page scope tabs --}}
        <div class="flex flex-wrap gap-1.5 rounded-xl border border-gray-200 bg-gray-50 p-1.5">
            @php
            $pages = [
                'global'      => ['icon' => '🌐', 'label' => 'Global',       'hint' => 'Applies to every page'],
                'home'        => ['icon' => '🏠', 'label' => 'Home',         'hint' => 'Homepage only'],
                'tours'       => ['icon' => '🗺️', 'label' => 'Tours List',   'hint' => '/tours listing page'],
                'tourDetail'  => ['icon' => '🎫', 'label' => 'Tour Detail',  'hint' => '/tours/{slug} pages'],
                'blog'        => ['icon' => '📰', 'label' => 'Blog',         'hint' => 'Blog listing & detail'],
                'contact'     => ['icon' => '📬', 'label' => 'Contact',      'hint' => 'Contact page'],
                'customPages' => ['icon' => '📄', 'label' => 'Custom Pages', 'hint' => 'Admin-created pages'],
            ];
            @endphp
            @foreach($pages as $pageKey => $pageMeta)
                <button
                    wire:click="$set('activePage', '{{ $pageKey }}')"
                    class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition {{ $activePage === $pageKey ? 'bg-white text-primary-700 shadow-sm ring-1 ring-gray-200' : 'text-gray-600 hover:text-gray-900 hover:bg-white/60' }}"
                    title="{{ $pageMeta['hint'] }}"
                >
                    <span>{{ $pageMeta['icon'] }}</span>
                    {{ $pageMeta['label'] }}
                    @php
                        $propMap = [
                            'global' => $customCss, 'home' => $homeCss, 'tours' => $toursCss,
                            'tourDetail' => $tourDetailCss, 'blog' => $blogCss,
                            'contact' => $contactCss, 'customPages' => $customPagesCss,
                        ];
                        $hasContent = !empty(trim($propMap[$pageKey] ?? ''));
                    @endphp
                    @if($hasContent)
                        <span class="h-1.5 w-1.5 rounded-full bg-green-500 flex-shrink-0"></span>
                    @endif
                </button>
            @endforeach
        </div>

        {{-- Active scope hint --}}
        <div class="flex items-center gap-2 text-xs text-gray-500 px-1">
            <span class="font-bold text-primary-700">{{ $pages[$activePage]['icon'] }} {{ $pages[$activePage]['label'] }}:</span>
            {{ $pages[$activePage]['hint'] }}
            @if($activePage !== 'global')
                <span class="text-gray-400">— loaded after Global CSS, so it can override anything.</span>
            @endif
        </div>

        {{-- Code editor panels (one per scope) --}}
        @foreach($pages as $pageKey => $pageMeta)
            @php
                $propName = match($pageKey) {
                    'home'        => 'homeCss',
                    'tours'       => 'toursCss',
                    'tourDetail'  => 'tourDetailCss',
                    'blog'        => 'blogCss',
                    'contact'     => 'contactCss',
                    'customPages' => 'customPagesCss',
                    default       => 'customCss',
                };
                $placeholder = match($pageKey) {
                    'global'      => "/* Global CSS — applies to EVERY public page */\n\n/* Change navbar background */\nheader { background: #ffffff !important; }\n\n/* Change body font */\nbody { font-family: 'Poppins', sans-serif; }",
                    'home'        => "/* Home page only */\n\n/* Style the hero section */\n.page-hero { min-height: 700px; }\n\n/* Style hero heading */\n.page-hero h1 { font-size: 4rem; letter-spacing: -0.03em; }",
                    'tours'       => "/* Tours listing page only */\n\n/* Style tour cards */\n.tours-grid article { border-radius: 1.5rem; }\n\n/* Style filter bar */\n.tours-filter { background: #f8fafc; }",
                    'tourDetail'  => "/* Tour detail pages only */\n\n/* Style the content area */\n.prose p { font-size: 1.0625rem; line-height: 1.8; }\n\n/* Style the booking sidebar */\n.booking-sidebar { border-radius: 1.5rem; }",
                    'blog'        => "/* Blog pages only */\n\n/* Style blog cards */\n.blog-card { border-radius: 1.25rem; }\n\n/* Style article content */\n.blog-detail-content .prose { font-size: 1.0625rem; }",
                    'contact'     => "/* Contact page only */\n\n/* Style the contact form */\n.contact-form input { border-radius: 0.75rem; }\n\n/* Style the map section */\n.contact-map { border-radius: 1.5rem; }",
                    'customPages' => "/* Custom / admin-created pages only */\n\n/* Style page content area */\n.custom-page-content { max-width: 48rem; margin: 0 auto; }\n\n/* Style page hero */\n.custom-page-hero { min-height: 400px; }",
                };
            @endphp
            <div
                @if($activePage !== $pageKey) style="display:none" @endif
                id="editor-{{ $pageKey }}"
            >
                <div class="rounded-xl border border-gray-200 bg-gray-900 overflow-hidden shadow-inner">
                    <div class="flex items-center justify-between px-4 py-2 bg-gray-800 border-b border-gray-700">
                        <span class="text-xs font-mono text-gray-400">
                            {{ $pageMeta['icon'] }} {{ $pageMeta['label'] }} — {{ $pageMeta['hint'] }}
                        </span>
                        <button
                            wire:click="$set('{{ $propName }}', '')"
                            class="text-xs text-gray-500 hover:text-red-400 transition"
                            onclick="if(!confirm('Clear CSS for this page scope?')) return false;"
                        >✕ Clear</button>
                    </div>
                    <textarea
                        id="css-ta-{{ $pageKey }}"
                        wire:model.live="{{ $propName }}"
                        rows="30"
                        spellcheck="false"
                        placeholder="{{ $placeholder }}"
                        class="w-full bg-gray-900 text-green-300 font-mono text-sm px-5 py-4 resize-none outline-none leading-relaxed placeholder:text-gray-600"
                        style="tab-size:2; -moz-tab-size:2;"
                    ></textarea>
                </div>
            </div>
        @endforeach

        <p class="text-xs text-gray-400 text-center">
            Changes take effect immediately after saving — reload the public site to see them.
        </p>
    </div>

    {{-- ══════════════════════════════════════
         RIGHT — Reference Library (2/5)
    ══════════════════════════════════════ --}}
    <div class="lg:col-span-2 flex flex-col gap-3">

        {{-- Tabs --}}
        <div class="flex rounded-lg border border-gray-200 overflow-hidden text-xs font-semibold">
            <button x-on:click="activeTab='snippets'"
                :class="activeTab==='snippets' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="flex-1 px-2 py-2 transition">✨ Snippets</button>
            <button x-on:click="activeTab='selectors'"
                :class="activeTab==='selectors' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="flex-1 px-2 py-2 transition">🎯 Selectors</button>
            <button x-on:click="activeTab='elements'"
                :class="activeTab==='elements' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="flex-1 px-2 py-2 transition">🗂️ Elements</button>
            <button x-on:click="activeTab='variables'"
                :class="activeTab==='variables' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                class="flex-1 px-2 py-2 transition">🎨 Variables</button>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white overflow-y-auto shadow-sm" style="max-height:740px;">

            {{-- ── TAB: SNIPPETS ── --}}
            <div x-show="activeTab==='snippets'" class="divide-y divide-gray-100">
                @php
                $snippetGroups = [

                    '🔝 Navbar & Header' => [
                        ['label' => 'Sticky white navbar',
                         'desc'  => 'Always-white with shadow',
                         'code'  => "header {\n  background: #ffffff !important;\n  box-shadow: 0 2px 16px rgba(0,0,0,.10);\n  position: sticky;\n  top: 0;\n  z-index: 50;\n}"],
                        ['label' => 'Transparent navbar',
                         'desc'  => 'See-through (best over hero)',
                         'code'  => "header {\n  background: transparent !important;\n  box-shadow: none;\n}"],
                        ['label' => 'Dark navbar',
                         'desc'  => 'Solid dark background',
                         'code'  => "header {\n  background: #0f172a !important;\n}\nheader nav a { color: #e2e8f0; }\nheader nav a:hover { color: #ffffff; }"],
                        ['label' => 'Larger nav links',
                         'desc'  => 'Increase font size',
                         'code'  => "header nav a {\n  font-size: 1rem;\n  font-weight: 600;\n  letter-spacing: 0.01em;\n}"],
                        ['label' => 'Taller navbar',
                         'desc'  => 'Increase navbar height',
                         'code'  => "header > div {\n  padding-top: 1.25rem;\n  padding-bottom: 1.25rem;\n}"],
                        ['label' => 'Active nav pill color',
                         'desc'  => 'Change the highlighted active link color',
                         'code'  => ".nav-pill-active,\nheader nav a[aria-current='page'] {\n  background: var(--color-accent) !important;\n  color: #ffffff !important;\n}"],
                    ],

                    '🌟 Hero Section' => [
                        ['label' => 'Taller hero',
                         'desc'  => 'Increase the hero height',
                         'code'  => ".page-hero {\n  min-height: 700px;\n}"],
                        ['label' => 'Shorter hero',
                         'desc'  => 'Reduce the hero height',
                         'code'  => ".page-hero {\n  min-height: 480px;\n}"],
                        ['label' => 'Darker hero overlay',
                         'desc'  => 'Better text contrast over image',
                         'code'  => ".page-hero__overlay {\n  background: rgba(0, 0, 0, 0.65) !important;\n}"],
                        ['label' => 'Hero title size',
                         'desc'  => 'Change the main h1 in the hero',
                         'code'  => ".page-hero h1 {\n  font-size: 4rem;\n  font-weight: 900;\n  line-height: 1.08;\n  letter-spacing: -0.03em;\n}"],
                        ['label' => 'Center hero content',
                         'desc'  => 'Center-align all hero text',
                         'code'  => ".page-hero .grid {\n  grid-template-columns: 1fr;\n  text-align: center;\n}\n.page-hero .grid > div { align-items: center; }"],
                        ['label' => 'Hero floating card style',
                         'desc'  => 'Restyle the floating tour cards',
                         'code'  => ".hero-stack-card {\n  border-radius: 2rem;\n  box-shadow: 0 40px 100px rgba(0,0,0,.30);\n}\n.hero-stack-card:hover {\n  transform: translateY(-8px);\n  transition: transform .3s ease;\n}"],
                        ['label' => 'Hero ticket-plan badge',
                         'desc'  => 'Style the "Ticket Plan" label',
                         'code'  => ".page-hero span:first-child {\n  background: rgba(236,72,153,.2);\n  color: #fbcfe8;\n  border-color: rgba(236,72,153,.3);\n}"],
                    ],

                    '🔍 Search Bar' => [
                        ['label' => 'Rounded search panel',
                         'desc'  => 'More rounded corners on the search form',
                         'code'  => "section form.rounded-2xl {\n  border-radius: 2rem;\n  box-shadow: 0 20px 60px rgba(0,0,0,.12);\n}"],
                        ['label' => 'Search bar background',
                         'desc'  => 'Change the search bar wrapper background',
                         'code'  => "section:has(form[x-data]) {\n  background: linear-gradient(to bottom, #f1f5f9, #ffffff);\n}"],
                        ['label' => 'Search button style',
                         'desc'  => 'Customize the search submit button',
                         'code'  => "form[x-data] button[type='submit'] {\n  background: linear-gradient(135deg, var(--color-primary), var(--color-accent));\n  border-radius: 9999px;\n  padding: 0.875rem 2.5rem;\n}"],
                    ],

                    '🎴 Featured Tour Cards' => [
                        ['label' => 'Card shadow & lift on hover',
                         'desc'  => 'Cards float up on hover',
                         'code'  => "#featured-tours article,\n.tours-grid article {\n  transition: transform .25s ease, box-shadow .25s ease;\n}\n#featured-tours article:hover,\n.tours-grid article:hover {\n  transform: translateY(-8px);\n  box-shadow: 0 40px 80px rgba(0,0,0,.18);\n}"],
                        ['label' => 'Card border radius',
                         'desc'  => 'More rounded tour cards',
                         'code'  => "#featured-tours article,\n.tours-grid article {\n  border-radius: 2rem;\n}"],
                        ['label' => 'Card image height',
                         'desc'  => 'Taller image area in cards',
                         'code'  => ".card-media {\n  height: 14rem;\n}"],
                        ['label' => 'Card price color',
                         'desc'  => 'Change tour price highlight',
                         'code'  => ".card-price,\n[class*='text-[var(--color-primary)]'] {\n  color: #16a34a;\n  font-size: 1.25rem;\n  font-weight: 900;\n}"],
                        ['label' => 'Card background',
                         'desc'  => 'Light gradient card background',
                         'code'  => "#featured-tours article,\n.tours-grid article {\n  background: linear-gradient(160deg, #ffffff 0%, #f8fafc 100%);\n}"],
                    ],

                    '🌍 Destinations Grid' => [
                        ['label' => 'Destination card hover zoom',
                         'desc'  => 'Image zooms smoothly on hover',
                         'code'  => ".top-destination-card img {\n  transition: transform .5s cubic-bezier(.25,.46,.45,.94);\n}\n.top-destination-card:hover img {\n  transform: scale(1.08);\n}"],
                        ['label' => 'Destination card overlay',
                         'desc'  => 'Darker gradient on destination cards',
                         'code'  => ".top-destination-card::after {\n  content: '';\n  position: absolute;\n  inset: 0;\n  background: linear-gradient(to top, rgba(0,0,0,.7) 0%, transparent 55%);\n}"],
                        ['label' => 'Destination card radius',
                         'desc'  => 'More rounded destination cards',
                         'code'  => ".top-destination-card {\n  border-radius: 2.5rem;\n}"],
                    ],

                    '🪟 Glass Cards (About, Stats, etc.)' => [
                        ['label' => 'Frosted glass style',
                         'desc'  => 'True glass effect with blur',
                         'code'  => ".glass-card {\n  background: rgba(255,255,255,.7) !important;\n  backdrop-filter: blur(16px);\n  -webkit-backdrop-filter: blur(16px);\n  border: 1px solid rgba(255,255,255,.5) !important;\n}"],
                        ['label' => 'About feature cards',
                         'desc'  => 'Style the 4 feature cards in About section',
                         'code'  => "section .rounded-\\[2rem\\] {\n  border-radius: 1.5rem;\n  padding: 2rem;\n  box-shadow: 0 8px 32px rgba(0,0,0,.08);\n}"],
                        ['label' => 'Stat number color',
                         'desc'  => 'Change the large stat numbers',
                         'code'  => ".glass-card .text-4xl {\n  color: var(--color-accent);\n  font-size: 3rem;\n}"],
                    ],

                    '⭐ Testimonials' => [
                        ['label' => 'Testimonial card style',
                         'desc'  => 'Bordered testimonial cards',
                         'code'  => "#testimonials .glass-card {\n  border: 2px solid color-mix(in srgb, var(--color-primary) 15%, transparent);\n  border-radius: 1.75rem;\n}"],
                        ['label' => 'Testimonial quote size',
                         'desc'  => 'Larger quote text',
                         'code'  => "#testimonials .text-3xl {\n  font-size: 1.5rem;\n  line-height: 1.4;\n}"],
                        ['label' => 'Testimonial author color',
                         'desc'  => 'Change author name color',
                         'code'  => "#testimonials .text-\\[var\\(--color-accent\\)\\] {\n  color: var(--color-primary);\n}"],
                    ],

                    '🖼️ Gallery' => [
                        ['label' => 'Gallery image hover zoom',
                         'desc'  => 'Images zoom on hover',
                         'code'  => "#gallery img {\n  transition: transform .6s ease;\n}\n#gallery .overflow-hidden:hover img {\n  transform: scale(1.08);\n}"],
                        ['label' => 'Gallery card radius',
                         'desc'  => 'More rounded gallery tiles',
                         'code'  => "#gallery .overflow-hidden {\n  border-radius: 2rem;\n}"],
                        ['label' => 'Gallery grid gap',
                         'desc'  => 'Tighter gallery grid',
                         'code'  => "#gallery .grid {\n  gap: 1rem;\n}"],
                    ],

                    '❓ FAQ Accordion' => [
                        ['label' => 'Accordion header style',
                         'desc'  => 'Style accordion question headers',
                         'code'  => ".accordion-header {\n  background: #f8fafc;\n  border-radius: 0.75rem;\n  padding: 1rem 1.25rem;\n  font-weight: 700;\n}\n.accordion-header:hover {\n  background: color-mix(in srgb, var(--color-primary) 8%, white);\n}"],
                        ['label' => 'FAQ section background',
                         'desc'  => 'Soft background for FAQ section',
                         'code'  => "#faq {\n  background: linear-gradient(135deg, #f8fafc 0%, #fff 100%);\n}"],
                    ],

                    '📰 Newsletter Section' => [
                        ['label' => 'Newsletter card dark',
                         'desc'  => 'Dark form card in newsletter',
                         'code'  => "section:has(#newsletter-email) form {\n  background: #0f172a;\n  border-radius: 1.5rem;\n}"],
                        ['label' => 'Newsletter subscribe button',
                         'desc'  => 'Accent-colored subscribe button',
                         'code'  => "section:has(#newsletter-email) button[type='submit'] {\n  background: var(--color-accent);\n  font-weight: 700;\n  letter-spacing: 0.03em;\n}"],
                        ['label' => 'Newsletter email input',
                         'desc'  => 'Style the email input field',
                         'code'  => "#newsletter-email {\n  background: #1e293b;\n  border-color: #334155;\n  border-radius: 2rem;\n  padding: 0.875rem 1.5rem;\n}"],
                    ],

                    '🗺️ Tours Listing Page' => [
                        ['label' => 'Tours grid layout',
                         'desc'  => '2 or 3 column grid for tours',
                         'code'  => ".tours-grid {\n  grid-template-columns: repeat(3, 1fr);\n}\n@media (max-width: 768px) {\n  .tours-grid { grid-template-columns: 1fr; }\n}"],
                        ['label' => 'Active filter badge',
                         'desc'  => 'Highlighted filter tag style',
                         'code'  => ".filter-tag {\n  background: var(--color-primary);\n  color: #ffffff;\n  border-radius: 9999px;\n  padding: 0.35rem 1rem;\n  font-size: 0.75rem;\n  font-weight: 600;\n}"],
                        ['label' => 'Tours page header',
                         'desc'  => 'Style the page banner on tours page',
                         'code'  => ".page-hero {\n  min-height: 380px;\n}\n.page-hero h1 {\n  font-size: 3rem;\n}"],
                    ],

                    '🎫 Tour Detail Page' => [
                        ['label' => 'Wider content column',
                         'desc'  => 'Wider main content area',
                         'code'  => ".tour-detail-content,\n.prose {\n  max-width: 56rem;\n}"],
                        ['label' => 'Prose content style',
                         'desc'  => 'Style tour body text',
                         'code'  => ".prose p {\n  font-size: 1.0625rem;\n  line-height: 1.85;\n  color: #334155;\n}\n.prose h2 {\n  font-size: 1.5rem;\n  font-weight: 800;\n  color: #0f172a;\n  margin-top: 2rem;\n}"],
                        ['label' => 'Tab header style',
                         'desc'  => 'Style the Overview/Itinerary tabs',
                         'code'  => ".tabs-header button {\n  border-radius: 0.75rem 0.75rem 0 0;\n  font-weight: 700;\n}\n.tabs-header button.active {\n  background: var(--color-primary);\n  color: #ffffff;\n}"],
                        ['label' => 'Booking sidebar',
                         'desc'  => 'Style the booking/price sidebar',
                         'code'  => ".booking-sidebar {\n  border-radius: 1.5rem;\n  border: 2px solid color-mix(in srgb, var(--color-primary) 20%, transparent);\n  box-shadow: 0 20px 60px rgba(0,0,0,.10);\n}"],
                        ['label' => 'Tour price badge',
                         'desc'  => 'Large price highlight',
                         'code'  => ".tour-price,\n.tour-detail-price {\n  color: #16a34a;\n  font-size: 2.25rem;\n  font-weight: 900;\n}"],
                    ],

                    '📄 Custom (Admin) Pages' => [
                        ['label' => 'Custom page content width',
                         'desc'  => 'Narrower readable content column',
                         'code'  => ".custom-page-content {\n  max-width: 48rem;\n  margin: 0 auto;\n  padding: 2.5rem 1.5rem;\n}"],
                        ['label' => 'Custom page hero',
                         'desc'  => 'Style the banner on custom pages',
                         'code'  => ".custom-page-hero,\n.page-hero {\n  min-height: 360px;\n  background: linear-gradient(135deg, var(--color-primary), var(--color-accent));\n}"],
                        ['label' => 'Custom page prose',
                         'desc'  => 'Style rich-text content on custom pages',
                         'code'  => ".custom-page-content .prose {\n  font-size: 1.0625rem;\n  line-height: 1.8;\n}\n.custom-page-content .prose h2 {\n  color: var(--color-primary);\n  font-weight: 800;\n}"],
                    ],

                    '🔘 Buttons & CTAs' => [
                        ['label' => 'Pill-shaped buttons',
                         'desc'  => 'Fully rounded CTA buttons',
                         'code'  => ".btn-primary,\na.rounded-full, button.rounded-full {\n  border-radius: 9999px !important;\n}"],
                        ['label' => 'Square buttons',
                         'desc'  => 'Remove rounding from buttons',
                         'code'  => "a[class*='rounded'], button[class*='rounded'] {\n  border-radius: 6px !important;\n}"],
                        ['label' => 'Bigger CTA buttons',
                         'desc'  => 'Larger padding on primary buttons',
                         'code'  => "a[class*='bg-'][class*='text-white'],\nbutton[class*='bg-'][class*='text-white'] {\n  padding: 0.875rem 2.5rem;\n  font-size: 1rem;\n  font-weight: 700;\n}"],
                        ['label' => 'Button gradient',
                         'desc'  => 'Gradient primary buttons',
                         'code'  => "a[class*='bg-[var(--color-primary)]'],\nbutton[class*='bg-[var(--color-primary)]'] {\n  background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%) !important;\n}"],
                    ],

                    '📝 Typography' => [
                        ['label' => 'Import Google Font (Poppins)',
                         'desc'  => 'Add Poppins font + apply to body',
                         'code'  => "@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap');\n\nbody {\n  font-family: 'Poppins', sans-serif;\n}"],
                        ['label' => 'Import Google Font (Inter)',
                         'desc'  => 'Add Inter font + apply to body',
                         'code'  => "@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;800&display=swap');\n\nbody {\n  font-family: 'Inter', sans-serif;\n}"],
                        ['label' => 'Larger body text',
                         'desc'  => 'Increase base font size',
                         'code'  => "body {\n  font-size: 1.0625rem;\n  line-height: 1.75;\n}"],
                        ['label' => 'Section headings',
                         'desc'  => 'Style all section h2 titles',
                         'code'  => "section h2 {\n  font-size: 2.5rem;\n  font-weight: 900;\n  letter-spacing: -0.025em;\n  line-height: 1.15;\n}"],
                        ['label' => 'Section badge pills',
                         'desc'  => 'Style the colored label badges',
                         'code'  => ".badge-pill {\n  border-radius: 9999px;\n  padding: 0.375rem 1rem;\n  font-size: 0.7rem;\n  font-weight: 700;\n  letter-spacing: 0.08em;\n  text-transform: uppercase;\n}"],
                    ],

                    '🦶 Footer' => [
                        ['label' => 'Dark footer',
                         'desc'  => 'Solid dark background',
                         'code'  => "footer {\n  background: #0f172a !important;\n  color: #e2e8f0;\n}\nfooter p, footer span { color: #94a3b8; }"],
                        ['label' => 'Light footer',
                         'desc'  => 'Clean white footer',
                         'code'  => "footer {\n  background: #ffffff !important;\n  border-top: 1px solid #e2e8f0;\n}\nfooter p, footer span { color: #64748b; }"],
                        ['label' => 'Footer link color',
                         'desc'  => 'Change footer link colors',
                         'code'  => "footer a {\n  color: #94a3b8;\n  text-decoration: none;\n}\nfooter a:hover {\n  color: var(--color-primary);\n}"],
                        ['label' => 'Footer column headings',
                         'desc'  => 'Style footer heading titles',
                         'code'  => "footer h3 {\n  color: #ffffff;\n  font-size: 0.875rem;\n  letter-spacing: 0.08em;\n  text-transform: uppercase;\n}"],
                    ],

                    '🎨 Themes & Effects' => [
                        ['label' => 'Gradient background',
                         'desc'  => 'Full-page gradient background',
                         'code'  => ".gradient-bg {\n  background: linear-gradient(160deg,\n    color-mix(in srgb, var(--color-primary) 6%, white) 0%,\n    white 50%,\n    color-mix(in srgb, var(--color-accent) 4%, white) 100%);\n}"],
                        ['label' => 'Remove gradient background',
                         'desc'  => 'Use solid color background instead',
                         'code'  => ".gradient-bg {\n  background: var(--color-bg);\n}"],
                        ['label' => 'WhatsApp button style',
                         'desc'  => 'Restyle the WhatsApp floating button',
                         'code'  => "a[href*='wa.me'] {\n  background: #25d366;\n  box-shadow: 0 8px 24px rgba(37,211,102,.4);\n  border-radius: 9999px;\n}"],
                        ['label' => 'Back-to-top button',
                         'desc'  => 'Restyle the scroll-to-top button',
                         'code'  => "#back-to-top {\n  background: var(--color-primary);\n  color: #ffffff;\n  border: none;\n  box-shadow: 0 8px 24px rgba(0,0,0,.2);\n}"],
                    ],
                ];
                @endphp

                @foreach($snippetGroups as $groupName => $snippets)
                    <div x-data="{ open: false }">
                        <button
                            x-on:click="open = !open"
                            class="flex items-center justify-between w-full px-4 py-3 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition"
                        >
                            <span>{{ $groupName }}</span>
                            <svg x-bind:class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-transition class="divide-y divide-gray-50">
                            @foreach($snippets as $snippet)
                                <div class="px-4 py-2.5 bg-gray-50/50 hover:bg-primary-50/30 transition">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold text-gray-900">{{ $snippet['label'] }}</p>
                                            <p class="text-[10px] text-gray-500 mt-0.5">{{ $snippet['desc'] }}</p>
                                            <pre class="mt-1.5 text-[10px] text-gray-600 bg-gray-100 rounded p-1.5 overflow-x-auto whitespace-pre-wrap font-mono leading-relaxed">{{ $snippet['code'] }}</pre>
                                        </div>
                                        <button
                                            x-on:click="insertSnippet({{ Js::from($snippet['code']) }})"
                                            class="flex-shrink-0 rounded-lg bg-primary-600 px-2.5 py-1 text-[10px] font-semibold text-white hover:bg-primary-700 active:scale-95 transition mt-0.5"
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
                        'header'                        => 'The whole top navigation bar',
                        'header > div'                  => 'Navbar inner wrapper (for height)',
                        'header nav'                    => 'Navigation links wrapper',
                        'header nav a'                  => 'Individual nav links',
                        'header nav a:hover'            => 'Nav link hover state',
                        '.nav-pill-active'              => 'Active/current nav link',
                        'header .logo, header .site-logo' => 'Site logo/name in navbar',
                    ],
                    '🌟 Hero Section' => [
                        '.page-hero'                    => 'Hero section wrapper',
                        '.page-hero.page-hero--hero1'   => 'Hero with default background image',
                        '.page-hero__image'             => 'Hero background image div',
                        '.page-hero__overlay'           => 'Dark overlay over hero image',
                        '.page-hero h1'                 => 'Hero main headline',
                        '.page-hero p'                  => 'Hero subtitle text',
                        '.hero-stack-card'              => 'Floating tour preview cards',
                        '.hero-stack-card img'          => 'Images inside hero cards',
                        '.hero-stack-card:hover'        => 'Hero card hover state',
                    ],
                    '🔍 Search Bar' => [
                        'section:has(#newsletter-email) ~ section form' => 'Search form below hero',
                        'form[x-data]'                  => 'The tour search form',
                        'form[x-data] input[type=text]' => 'Keyword search input',
                        'form[x-data] select'           => 'Category / duration dropdowns',
                        'form[x-data] input[type=range]' => 'Max price slider',
                        "form[x-data] button[type='submit']" => 'Search submit button',
                    ],
                    '🏠 Homepage Sections' => [
                        '.page-section-header'          => 'Section heading block (badge + h2 + p)',
                        '.badge-pill'                   => 'Colored label pill in section headers',
                        'section h2'                    => 'All section titles',
                        'section p'                     => 'All section body paragraphs',
                        '#featured-tours'               => 'Featured tours grid section',
                        '#destinations'                 => 'Destinations grid section',
                        '#testimonials'                 => 'Testimonials section',
                        '#gallery'                      => 'Photo gallery section',
                        '#faq'                          => 'FAQ accordion section',
                    ],
                    '🎴 Tour Cards' => [
                        '#featured-tours article'       => 'Featured tour card (homepage)',
                        '#featured-tours .card-media'   => 'Image wrapper in tour card',
                        '#featured-tours article:hover' => 'Tour card hover state',
                        '.tours-grid article'           => 'Tour card on tours listing page',
                        '.tours-grid .card-media'       => 'Image wrapper on tours listing',
                    ],
                    '🌍 Destination Cards' => [
                        '.top-destination-card'         => 'Destination image cards',
                        '.top-destination-card img'     => 'Image inside destination card',
                        '.top-destination-card:hover'   => 'Destination card hover state',
                        '.top-destination-card h3'      => 'Destination card title',
                    ],
                    '🪟 Glass Cards' => [
                        '.glass-card'                   => 'Glass card component (used in About, Stats, etc.)',
                        '#testimonials .glass-card'     => 'Testimonial glass card',
                        '#testimonials .glass-card p'   => 'Testimonial text',
                        '.glass-card .text-4xl'         => 'Large stat numbers',
                    ],
                    '🗺️ Tours Listing Page' => [
                        '.tours-grid'                   => 'Tours listing grid',
                        '.tours-grid article'           => 'Individual tour card on listing',
                        '.filter-tag'                   => 'Active filter badge tag',
                        '.tours-filter'                 => 'Filter bar wrapper',
                    ],
                    '🎫 Tour Detail Page' => [
                        '.tour-detail-content'          => 'Main content column on tour detail',
                        '.booking-sidebar'              => 'Booking / price sidebar',
                        '.tour-price, .tour-detail-price' => 'Price display',
                        '.tabs-header'                  => 'Tab navigation header (Overview, Itinerary…)',
                        '.tabs-header button'           => 'Individual tab buttons',
                        '.tabs-header button.active'    => 'Active tab button',
                        '.accordion-header'             => 'Accordion question header',
                        '.accordion-body'               => 'Accordion expanded content',
                        '.prose'                        => 'Rich-text content (tour overview)',
                        '.prose p'                      => 'Paragraphs inside rich text',
                        '.prose h2'                     => 'Headings inside rich text',
                        '.prose ul li'                  => 'List items inside rich text',
                    ],
                    '📄 Custom Pages' => [
                        '.custom-page-content'          => 'Custom page content wrapper',
                        '.custom-page-hero'             => 'Custom page hero/banner',
                        '.custom-page-content .prose'   => 'Rich text inside custom pages',
                    ],
                    '📝 Typography' => [
                        'body'                          => 'Base font & color for entire site',
                        'h1'                            => 'All h1 headings',
                        'h2'                            => 'All h2 headings',
                        'h3'                            => 'All h3 headings',
                        'p'                             => 'All paragraph text',
                        '.prose p'                      => 'Rich text body (blog/tour content)',
                        '.prose h2'                     => 'Headings inside rich text',
                        '.prose a'                      => 'Links inside rich text',
                        '.prose ul li'                  => 'List items in rich text',
                    ],
                    '🔘 Buttons & CTAs' => [
                        'a.rounded-full'                => 'Pill-shaped link buttons',
                        'button.rounded-full'           => 'Pill-shaped button elements',
                        "a[class*='bg-'][class*='text-white']" => 'Primary CTA links',
                        "button[type='submit']"         => 'All submit buttons',
                    ],
                    '🦶 Footer' => [
                        'footer'                        => 'Entire footer area',
                        'footer > div'                  => 'Footer inner wrapper',
                        'footer h3'                     => 'Footer column headings',
                        'footer a'                      => 'Footer links',
                        'footer p'                      => 'Footer body text',
                        'footer .grid'                  => 'Footer column grid',
                    ],
                    '🎨 Global Layout' => [
                        '.gradient-bg'                  => 'Full-page background wrapper',
                        '#back-to-top'                  => 'Back-to-top scroll button',
                        "a[href*='wa.me']"              => 'WhatsApp floating button',
                        '.x-chatbot-widget'             => 'AI chatbot widget',
                    ],
                ];
                @endphp

                @foreach($selectorGroups as $groupName => $selectors)
                    <div x-data="{ open: false }">
                        <button
                            x-on:click="open = !open"
                            class="flex items-center justify-between w-full px-4 py-3 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition"
                        >
                            <span>{{ $groupName }}</span>
                            <svg x-bind:class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-transition class="divide-y divide-gray-50">
                            @foreach($selectors as $selector => $description)
                                <div class="flex items-center justify-between gap-2 px-4 py-2 bg-gray-50/40 hover:bg-primary-50/30 transition">
                                    <div class="flex-1 min-w-0">
                                        <code class="text-[10px] font-mono font-bold text-primary-700 bg-primary-50 px-1.5 py-0.5 rounded break-all">{{ $selector }}</code>
                                        <p class="text-[10px] text-gray-500 mt-0.5">{{ $description }}</p>
                                    </div>
                                    <button
                                        x-on:click="insertSnippet({{ Js::from($selector . " {\n  /* your styles here */\n}") }})"
                                        class="flex-shrink-0 rounded-lg border border-gray-200 bg-white px-2 py-1 text-[10px] font-medium text-gray-700 hover:bg-primary-600 hover:text-white hover:border-primary-600 active:scale-95 transition"
                                    >+ Insert</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ── TAB: ELEMENTS (Visual Tree) ── --}}
            <div x-show="activeTab==='elements'" class="p-3 space-y-3">
                <p class="text-[10px] text-gray-500 leading-relaxed">
                    Browse every page element and its CSS class. Click <strong>+ Target</strong> to insert a ready-to-edit CSS block for that element.
                </p>

                @php
                $elementTree = [
                    '🌐 Site-wide' => [
                        ['name' => 'Whole page background',   'el' => '.gradient-bg',     'desc' => 'The outermost page wrapper'],
                        ['name' => 'Body text & font',        'el' => 'body',              'desc' => 'Base typography for every page'],
                        ['name' => 'Back-to-top button',      'el' => '#back-to-top',      'desc' => 'Fixed scroll button (bottom-right)'],
                        ['name' => 'WhatsApp button',         'el' => "a[href*='wa.me']",  'desc' => 'Fixed WhatsApp chat button (bottom-left)'],
                    ],
                    '🔝 Navbar' => [
                        ['name' => 'Navbar bar',              'el' => 'header',                        'desc' => 'The top navigation bar'],
                        ['name' => 'Nav links container',     'el' => 'header nav',                    'desc' => 'Wraps all nav link items'],
                        ['name' => 'Nav link',                'el' => 'header nav a',                  'desc' => 'Each navigation link'],
                        ['name' => 'Active nav link',         'el' => '.nav-pill-active',              'desc' => 'The currently active nav item'],
                        ['name' => 'Mobile menu drawer',      'el' => 'header [x-show]',               'desc' => 'Mobile hamburger menu panel'],
                    ],
                    '🌟 Hero (Homepage)' => [
                        ['name' => 'Hero section',            'el' => '.page-hero',                    'desc' => 'The full hero banner area'],
                        ['name' => 'Hero background image',   'el' => '.page-hero__image',             'desc' => 'The background image div'],
                        ['name' => 'Hero dark overlay',       'el' => '.page-hero__overlay',           'desc' => 'Dark semi-transparent overlay'],
                        ['name' => 'Hero h1 title',           'el' => '.page-hero h1',                 'desc' => 'Main hero headline'],
                        ['name' => 'Hero subtitle',           'el' => '.page-hero > div p',            'desc' => 'Hero description text'],
                        ['name' => 'Hero CTA buttons row',    'el' => '.page-hero .flex.flex-wrap',    'desc' => 'Row containing the CTA buttons'],
                        ['name' => 'Floating tour card',      'el' => '.hero-stack-card',              'desc' => 'Floating destination preview cards'],
                        ['name' => 'Floating card image',     'el' => '.hero-stack-card img',          'desc' => 'Image inside each floating card'],
                    ],
                    '🔍 Search Bar' => [
                        ['name' => 'Search form panel',       'el' => 'form[x-data]',                  'desc' => 'The tour search form'],
                        ['name' => 'Keyword input',           'el' => 'form[x-data] input[type=text]', 'desc' => 'Tour keyword text field'],
                        ['name' => 'Category dropdown',       'el' => 'form[x-data] select',           'desc' => 'Category & duration selects'],
                        ['name' => 'Price slider',            'el' => 'form[x-data] input[type=range]','desc' => 'Max price range slider'],
                        ['name' => 'Search button',           'el' => "form[x-data] button[type='submit']", 'desc' => 'Submit / search button'],
                    ],
                    '🏠 Sections (Homepage)' => [
                        ['name' => 'Section header block',    'el' => '.page-section-header',          'desc' => 'Badge + h2 + description above sections'],
                        ['name' => 'Section badge pill',      'el' => '.badge-pill',                   'desc' => 'Colored label "Featured Tours", etc.'],
                        ['name' => 'Section h2 title',        'el' => 'section h2',                    'desc' => 'All section titles'],
                        ['name' => 'Featured Tours section',  'el' => '#featured-tours',               'desc' => 'Featured tours grid section'],
                        ['name' => 'Featured tour card',      'el' => '#featured-tours article',       'desc' => 'Each tour card in featured section'],
                        ['name' => 'Tour card image area',    'el' => '.card-media',                   'desc' => 'Image wrapper inside tour cards'],
                        ['name' => 'About section',           'el' => '#about',                        'desc' => 'About / commitments section'],
                        ['name' => 'About feature card',      'el' => "section .rounded-\\[2rem\\]",   'desc' => 'The 4 feature cards in about section'],
                        ['name' => 'Destinations section',    'el' => '#destinations',                 'desc' => 'Top destinations grid'],
                        ['name' => 'Destination card',        'el' => '.top-destination-card',         'desc' => 'Each destination image card'],
                        ['name' => 'Testimonials section',    'el' => '#testimonials',                 'desc' => 'Guest reviews section'],
                        ['name' => 'Testimonial card',        'el' => '#testimonials .glass-card',     'desc' => 'Each testimonial card'],
                        ['name' => 'Stats section',           'el' => '#statistics',                   'desc' => 'Statistics number counters section'],
                        ['name' => 'Stat number',             'el' => '.glass-card .text-4xl',         'desc' => 'Large number in stat cards'],
                        ['name' => 'Gallery section',         'el' => '#gallery',                      'desc' => 'Photo gallery section'],
                        ['name' => 'Gallery image tile',      'el' => '#gallery .overflow-hidden',     'desc' => 'Each gallery image tile'],
                        ['name' => 'FAQ section',             'el' => '#faq',                          'desc' => 'FAQ accordion section'],
                        ['name' => 'Accordion header',        'el' => '.accordion-header',             'desc' => 'Accordion question header button'],
                        ['name' => 'Accordion body',          'el' => '.accordion-body',               'desc' => 'Accordion expanded answer area'],
                        ['name' => 'Newsletter section',      'el' => '#newsletter',                   'desc' => 'Newsletter sign-up section'],
                        ['name' => 'Newsletter email input',  'el' => '#newsletter-email',             'desc' => 'Email input in newsletter form'],
                        ['name' => 'Contact section',         'el' => '#contact',                      'desc' => 'Contact information section'],
                        ['name' => 'Glass card',              'el' => '.glass-card',                   'desc' => 'Glassmorphism card component'],
                    ],
                    '🗺️ Tours Listing Page' => [
                        ['name' => 'Page banner',             'el' => '.page-hero',                    'desc' => 'Tours page top banner'],
                        ['name' => 'Tours grid',              'el' => '.tours-grid',                   'desc' => 'Grid of all tour cards'],
                        ['name' => 'Tour listing card',       'el' => '.tours-grid article',           'desc' => 'Each tour card in listing'],
                        ['name' => 'Active filter badge',     'el' => '.filter-tag',                   'desc' => 'Active search filter tag'],
                        ['name' => 'Filter bar',              'el' => '.tours-filter',                 'desc' => 'Filter options bar'],
                    ],
                    '🎫 Tour Detail Page' => [
                        ['name' => 'Page banner',             'el' => '.page-hero',                    'desc' => 'Tour detail top banner'],
                        ['name' => 'Main content col',        'el' => '.lg\\:col-span-2',              'desc' => 'Left content column'],
                        ['name' => 'Booking sidebar',         'el' => '.booking-sidebar',              'desc' => 'Right price/booking column'],
                        ['name' => 'Tab navigation',          'el' => '.tabs-header',                  'desc' => 'Overview / Itinerary tabs bar'],
                        ['name' => 'Active tab button',       'el' => '.tabs-header button.active',    'desc' => 'Currently selected tab'],
                        ['name' => 'Rich text prose',         'el' => '.prose',                        'desc' => 'Rich-text content area'],
                        ['name' => 'Prose paragraphs',        'el' => '.prose p',                      'desc' => 'Body text inside rich text'],
                        ['name' => 'Prose headings',          'el' => '.prose h2',                     'desc' => 'Headings inside rich text'],
                        ['name' => 'Tour price',              'el' => '.tour-price',                   'desc' => 'Price display element'],
                        ['name' => 'Accordion (itinerary)',   'el' => '.accordion-header',             'desc' => 'Itinerary day accordion headers'],
                    ],
                    '📄 Custom / Admin Pages' => [
                        ['name' => 'Page content area',       'el' => '.custom-page-content',          'desc' => 'Content wrapper on custom pages'],
                        ['name' => 'Page hero banner',        'el' => '.custom-page-hero',             'desc' => 'Top banner on custom pages'],
                        ['name' => 'Page rich text',          'el' => '.custom-page-content .prose',   'desc' => 'Rich text content on custom pages'],
                    ],
                    '🦶 Footer' => [
                        ['name' => 'Footer wrapper',          'el' => 'footer',                        'desc' => 'Entire footer area'],
                        ['name' => 'Footer grid',             'el' => 'footer .grid',                  'desc' => 'Footer column layout grid'],
                        ['name' => 'Footer heading',          'el' => 'footer h3',                     'desc' => 'Footer column headings'],
                        ['name' => 'Footer links',            'el' => 'footer a',                      'desc' => 'All footer links'],
                        ['name' => 'Footer body text',        'el' => 'footer p',                      'desc' => 'Footer paragraphs & descriptions'],
                    ],
                ];
                @endphp

                @foreach($elementTree as $groupName => $elements)
                    <div x-data="{ open: false }">
                        <button
                            x-on:click="open = !open"
                            class="flex items-center justify-between w-full px-3 py-2 text-xs font-bold text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition"
                        >
                            <span>{{ $groupName }}</span>
                            <svg x-bind:class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-transition class="mt-1 divide-y divide-gray-100 rounded-lg border border-gray-100 overflow-hidden">
                            @foreach($elements as $element)
                                <div class="flex items-center justify-between gap-2 px-3 py-2 bg-white hover:bg-indigo-50/40 transition">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[10px] font-semibold text-gray-900">{{ $element['name'] }}</p>
                                        <code class="text-[9px] font-mono text-indigo-700 bg-indigo-50 px-1 py-0.5 rounded mt-0.5 block break-all">{{ $element['el'] }}</code>
                                        <p class="text-[9px] text-gray-400 mt-0.5">{{ $element['desc'] }}</p>
                                    </div>
                                    <button
                                        x-on:click="insertSnippet({{ Js::from($element['el'] . " {\n  /* " . $element['desc'] . " */\n}") }})"
                                        class="flex-shrink-0 rounded-lg border border-indigo-200 bg-indigo-50 px-2 py-1 text-[9px] font-semibold text-indigo-700 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 active:scale-95 transition"
                                    >+ Target</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ── TAB: CSS VARIABLES ── --}}
            <div x-show="activeTab==='variables'" class="p-4 space-y-4">
                <p class="text-xs text-gray-500 leading-relaxed">
                    These CSS variables are set by Theme Settings and flow through the entire site. Override them here or use them inside your custom CSS rules.
                </p>

                @php
                $variables = [
                    '--color-primary'        => ['label' => 'Primary Color',          'desc' => 'Navbar links, active pills, tour prices, CTA borders. Set in Theme → Theme Settings.', 'example' => '#0ea5e9'],
                    '--color-accent'         => ['label' => 'Accent Color',            'desc' => 'Buttons, sale badges, search button, newsletter CTA. Set in Theme → Theme Settings.',   'example' => '#f97316'],
                    '--color-bg'             => ['label' => 'Background Color',        'desc' => 'Site-wide page background color. Set in Theme → Theme Settings.',                         'example' => '#eef9fb'],
                    '--font-family'          => ['label' => 'Font Family',             'desc' => 'Main typeface for the whole site. Set in Theme → Theme Settings.',                         'example' => 'Figtree, sans-serif'],
                    '--hero-overlay-opacity' => ['label' => 'Hero Overlay Opacity',    'desc' => 'Controls darkness of the hero image overlay (0 = transparent, 1 = solid black).',         'example' => '0.45'],
                ];
                @endphp

                <div class="rounded-xl border border-gray-200 overflow-hidden divide-y divide-gray-100">
                    @foreach($variables as $varName => $meta)
                        <div class="p-3 bg-white hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <code class="text-xs font-mono font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded">{{ $varName }}</code>
                                <button
                                    x-on:click="insertSnippet({{ Js::from(':root {' . "\n  " . $varName . ': ' . $meta['example'] . ';\n}') }})"
                                    class="text-[10px] text-gray-500 hover:text-primary-600 transition font-semibold"
                                >Override ↗</button>
                            </div>
                            <p class="text-xs font-semibold text-gray-700">{{ $meta['label'] }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $meta['desc'] }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="rounded-xl bg-amber-50 border border-amber-200 p-4">
                    <p class="text-xs font-semibold text-amber-800 mb-2">💡 How to use variables in CSS</p>
                    <pre class="text-xs font-mono text-amber-700 leading-relaxed whitespace-pre-wrap">/* Reference an existing variable */
.my-element {
  color: var(--color-primary);
  background: var(--color-accent);
  border-color: color-mix(
    in srgb, var(--color-primary) 20%, transparent
  );
}

/* Override a variable globally */
:root {
  --color-primary: #2563eb;
  --color-accent: #16a34a;
}</pre>
                </div>

                <div class="rounded-xl bg-blue-50 border border-blue-200 p-4">
                    <p class="text-xs font-semibold text-blue-800 mb-2">🎨 Prefer color pickers?</p>
                    <p class="text-xs text-blue-700">Go to <strong>Appearance → Theme Settings</strong> to change colors and fonts using visual pickers — no CSS needed.</p>
                </div>
            </div>

        </div>{{-- end reference panel --}}

        <p class="text-xs text-gray-400 text-center">
            Tip: Click a scope tab (e.g. <strong>Tour Detail</strong>) first, then click <strong>+ Insert</strong> to target only that page.
        </p>
    </div>

</div>{{-- end editor+reference grid --}}

</div>{{-- end toolbar x-data wrapper --}}

{{-- Re-show the correct editor when page tab switches via Livewire --}}
<script>
document.addEventListener('livewire:updated', () => {
    const activePage = @this.activePage;
    document.querySelectorAll('[id^="editor-"]').forEach(el => {
        el.style.display = el.id === 'editor-' + activePage ? '' : 'none';
    });
});
</script>

</x-filament-panels::page>
