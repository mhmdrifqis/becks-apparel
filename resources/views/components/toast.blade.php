<div
    x-data="{
        messages: [],
        remove(id) {
            this.messages = this.messages.filter(m => m.id !== id)
        },
        add(message, type = 'success') {
            const id = Date.now()
            this.messages.push({ id, message, type })
            setTimeout(() => this.remove(id), 5000)
        }
    }"
    @notify.window="add($event.detail.message, $event.detail.type || 'success')"
    class="fixed top-6 right-4 md:right-8 z-[9999] flex flex-col items-end gap-3 pointer-events-none"
>
    <template x-for="msg in messages" :key="msg.id">
        <div
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-[-20px] scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-32"
            class="pointer-events-auto bg-white/90 backdrop-blur-xl dark:bg-black/80 border border-slate-100 dark:border-white/10 rounded-[1.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.1)] p-4 pr-12 min-w-[320px] max-w-[400px] flex items-center gap-4 relative overflow-hidden group"
        >
            <!-- Animated Border / Progress -->
            <div class="absolute bottom-0 left-0 w-full h-[3px] bg-slate-50 dark:bg-white/5">
                <div 
                    class="h-full transition-all linear" 
                    :class="{
                        'bg-brand-900': msg.type === 'success',
                        'bg-amber-500': msg.type === 'warning',
                        'bg-rose-500': msg.type === 'error'
                    }"
                    x-init="$el.style.width = '100%'; setTimeout(() => $el.style.width = '0%', 50)" 
                    style="transition-duration: 5000ms"
                ></div>
            </div>

            <!-- Icon Wrapper -->
            <div :class="{
                'bg-brand-50 text-brand-900': msg.type === 'success',
                'bg-amber-50 text-amber-600': msg.type === 'warning',
                'bg-rose-50 text-rose-600': msg.type === 'error'
            }" class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0">
                <template x-if="msg.type === 'success'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </template>
                <template x-if="msg.type === 'error'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </template>
                <template x-if="msg.type === 'warning'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </template>
            </div>

            <!-- Content -->
            <div class="flex-1 py-1">
                <h4 class="text-[10px] font-black uppercase tracking-[0.2em] opacity-40 mb-1" x-text="msg.type"></h4>
                <p class="text-sm font-black text-slate-800 dark:text-white leading-[1.3] tracking-tight" x-text="msg.message"></p>
            </div>

            <!-- Close Action -->
            <button @click="remove(msg.id)" class="absolute top-4 right-4 w-6 h-6 flex items-center justify-center rounded-lg hover:bg-slate-50 dark:hover:bg-white/5 text-slate-300 hover:text-slate-900 dark:hover:text-white transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </template>
</div>
