{{-- AI Chatbot Widget — floating bottom-right --}}
@php
    $locale = $currentLocale ?? app()->getLocale();
@endphp

<div
    x-data="{
        open: false,
        messages: [
            { role: 'bot', text: 'Hi! 👋 I\'m your travel assistant. Ask me about tours, prices, destinations, or how to book. How can I help?' }
        ],
        input: '',
        loading: false,
        unread: 0,
        async send() {
            const text = this.input.trim();
            if (!text || this.loading) return;
            this.input = '';
            this.messages.push({ role: 'user', text });
            this.loading = true;
            this.$nextTick(() => this.scrollToBottom());
            try {
                const res = await fetch('{{ url('/chatbot') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ message: text }),
                });
                const data = await res.json();
                this.messages.push({ role: 'bot', text: data.reply ?? 'Sorry, I could not process that.' });
                if (!this.open) this.unread++;
            } catch(e) {
                this.messages.push({ role: 'bot', text: 'Oops! Something went wrong. Please try again or contact us directly.' });
            } finally {
                this.loading = false;
                this.$nextTick(() => this.scrollToBottom());
            }
        },
        scrollToBottom() {
            const el = this.$refs.chatBody;
            if (el) el.scrollTop = el.scrollHeight;
        },
        formatText(text) {
            return text
                .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
                .replace(/~~(.+?)~~/g, '<del>$1</del>')
                .replace(/\n/g, '<br>');
        },
        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.unread = 0;
                this.$nextTick(() => {
                    this.scrollToBottom();
                    this.$refs.chatInput?.focus();
                });
            }
        }
    }"
    class="fixed bottom-6 right-6 z-[9998] flex flex-col items-end gap-3"
>
    {{-- Chat panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="w-80 sm:w-96 rounded-2xl border border-white/20 bg-white shadow-2xl overflow-hidden flex flex-col"
        style="max-height: min(560px, calc(100vh - 100px));"
        x-cloak
    >
        {{-- Header --}}
        <div class="bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-accent)] px-4 py-3 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="relative flex h-9 w-9 items-center justify-center rounded-full bg-white/20">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>
                    </svg>
                    <span class="absolute -right-0.5 -top-0.5 h-2.5 w-2.5 rounded-full border-2 border-white bg-green-400"></span>
                </div>
                <div>
                    <p class="text-sm font-bold text-white">Travel Assistant</p>
                    <p class="text-xs text-white/70">Online · Replies instantly</p>
                </div>
            </div>
            <button @click="toggle()" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20 text-white transition hover:bg-white/30">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Messages --}}
        <div x-ref="chatBody" class="flex-1 overflow-y-auto p-4 space-y-3 bg-slate-50">
            <template x-for="(msg, i) in messages" :key="i">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    {{-- Bot avatar --}}
                    <div x-show="msg.role === 'bot'" class="mr-2 flex-shrink-0 flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-accent)] self-end">
                        <svg class="h-3.5 w-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                        </svg>
                    </div>
                    <div
                        :class="msg.role === 'user'
                            ? 'bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-accent)] text-white rounded-2xl rounded-br-sm'
                            : 'bg-white border border-slate-200 text-slate-800 rounded-2xl rounded-bl-sm shadow-sm'"
                        class="max-w-[85%] px-3.5 py-2.5 text-sm leading-relaxed"
                        x-html="formatText(msg.text)"
                    ></div>
                </div>
            </template>

            {{-- Typing indicator --}}
            <div x-show="loading" class="flex justify-start">
                <div class="mr-2 flex-shrink-0 flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-accent)] self-end">
                    <svg class="h-3.5 w-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                    </svg>
                </div>
                <div class="flex items-center gap-1 rounded-2xl rounded-bl-sm bg-white border border-slate-200 px-4 py-3 shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-slate-400 animate-bounce" style="animation-delay:0ms"></span>
                    <span class="h-2 w-2 rounded-full bg-slate-400 animate-bounce" style="animation-delay:150ms"></span>
                    <span class="h-2 w-2 rounded-full bg-slate-400 animate-bounce" style="animation-delay:300ms"></span>
                </div>
            </div>
        </div>

        {{-- Quick suggestions --}}
        <div class="flex flex-wrap gap-1.5 px-3 py-2 bg-white border-t border-slate-100 flex-shrink-0">
            <template x-for="suggestion in ['Show tours', 'Pricing', 'Destinations', 'How to book', 'Contact']">
                <button
                    @click="input = suggestion; send()"
                    class="rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs text-slate-600 transition hover:border-[var(--color-accent)] hover:text-[var(--color-accent)]"
                    x-text="suggestion"
                ></button>
            </template>
        </div>

        {{-- Input --}}
        <div class="border-t border-slate-100 bg-white p-3 flex items-center gap-2 flex-shrink-0">
            <input
                x-ref="chatInput"
                x-model="input"
                @keydown.enter.prevent="send()"
                type="text"
                maxlength="500"
                placeholder="Ask me anything…"
                class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-950 placeholder-slate-400 focus:border-[var(--color-accent)] focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/20 transition"
            >
            <button
                @click="send()"
                :disabled="!input.trim() || loading"
                class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-accent)] text-white shadow transition hover:opacity-90 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- FAB button --}}
    <button
        @click="toggle()"
        class="relative flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-accent)] text-white shadow-lg transition hover:scale-105 hover:shadow-xl active:scale-95"
        :aria-label="open ? 'Close chat' : 'Open chat'"
    >
        {{-- Unread badge --}}
        <span
            x-show="unread > 0 && !open"
            class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white"
            x-text="unread"
        ></span>

        <span x-show="!open">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>
            </svg>
        </span>
        <span x-show="open">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </span>

        {{-- Pulse ring when closed --}}
        <span x-show="!open" class="absolute inset-0 rounded-full animate-ping bg-[var(--color-accent)]/30" style="animation-duration:2.5s"></span>
    </button>
</div>
