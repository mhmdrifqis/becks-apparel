<x-filament-panels::page>
    <div wire:poll.3s="loadActiveChats" class="grid grid-cols-1 md:grid-cols-3 gap-6 h-[70vh]">

        <!-- Sidebar: Active Chats -->
        <div class="col-span-1 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 flex flex-col overflow-hidden">

            <!-- Header Sidebar -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800">
                <h3 class="text-lg font-bold text-black dark:text-white">
                    Obrolan Aktif ({{ count($activeChats) }})
                </h3>
            </div>

            <div class="flex-1 overflow-y-auto p-2">
                @forelse($activeChats as $chat)
                    <button
                        wire:click="selectChat({{ $chat->id }})"
                        class="w-full text-left p-3 mb-2 rounded-lg transition-colors border {{ $selectedChatId == $chat->id ? 'bg-primary-50 border-primary-500 dark:bg-primary-900/20 dark:border-primary-500' : 'bg-transparent border-transparent hover:bg-gray-50 dark:hover:bg-gray-800' }}"
                    >
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                {{ $chat->user_name ?? 'Guest User' }}
                                @if($chat->unread_count > 0)
                                    <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                        {{ $chat->unread_count }}
                                    </span>
                                @endif
                            </span>

                            <span class="text-xs text-gray-500">
                                {{ $chat->created_at->format('H:i') }}
                            </span>
                        </div>

                        <div class="text-xs text-gray-500 mt-1 truncate">
                            {{ $chat->latest_message ? \Illuminate\Support\Str::limit($chat->latest_message, 40) : 'ID: ' . substr($chat->user_id, 0, 15) }}
                        </div>
                    </button>
                @empty
                    <div class="text-center p-4 text-sm text-gray-500 mt-10">
                        Tidak ada pelanggan yang meminta bantuan admin saat ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="col-span-1 md:col-span-2 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 flex flex-col overflow-hidden">

            @if($selectedChatId)

                <!-- Header Chat -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 flex justify-between items-center">

                    <h3 class="text-lg font-bold text-black dark:text-white">
                        Obrolan dengan Pelanggan
                    </h3>

                    <x-filament::button
                        wire:click="endSession"
                        color="danger"
                        size="sm"
                        icon="heroicon-o-x-circle">
                        Akhiri Sesi
                    </x-filament::button>

                </div>

                <!-- Messages -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-box">

                    @forelse($messages as $msg)

                        @if($msg->sender == 'admin')

                            <div class="flex items-start gap-3 flex-row-reverse">
                                <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center flex-shrink-0 text-white font-bold text-xs">
                                    CS
                                </div>

                                <div class="bg-primary-600 text-white px-4 py-2 rounded-2xl rounded-tr-none shadow-sm text-sm max-w-[80%] relative group">
                                    <div class="pr-6">
                                        {{ $msg->message }}
                                    </div>
                                    <div class="absolute bottom-1 right-2 text-[10px] flex items-center">
                                        @if($msg->is_read)
                                            <!-- Double Blue Ticks (Since bg is colored, we can use a light blue or just white for contrast, but let's use a distinct color like #38bdf8 (sky-400) or just plain white/gray depending on design. Since it's WhatsApp style, blue on green/primary bg. -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                                <path d="M20 12l-11 11-1-1"></path>
                                            </svg>
                                        @else
                                            <!-- Double Gray Ticks -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                                <path d="M20 12l-11 11-1-1"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        @else

                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 text-gray-600 dark:text-gray-300 font-bold text-xs">
                                    U
                                </div>

                                <div class="bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-2xl rounded-tl-none border border-gray-200 dark:border-gray-700 shadow-sm text-sm max-w-[80%]">
                                    {{ $msg->message }}
                                </div>
                            </div>

                        @endif

                    @empty

                        <div class="text-center text-sm text-gray-500">
                            Belum ada pesan.
                        </div>

                    @endforelse

                </div>

                <!-- Input -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800">

                    <form wire:submit.prevent="sendMessage" class="flex gap-2">

                        <input
                            type="text"
                            wire:model="newMessage"
                            placeholder="Tulis balasan..."
                            class="flex-1 rounded-lg border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            required
                        >

                        <x-filament::button type="submit" color="success">
                            Kirim
                        </x-filament::button>

                    </form>

                </div>

            @else

                <div class="flex-1 flex flex-col items-center justify-center text-gray-500">
                    <p>Pilih salah satu obrolan di samping untuk mulai membalas.</p>
                </div>

            @endif

        </div>

    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const scrollToBottom = () => {
                const chatBox = document.getElementById('chat-box');
                if (chatBox) {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            };
            
            // Scroll on load
            scrollToBottom();

            // Scroll after Livewire updates
            Livewire.hook('morph.updated', () => {
                scrollToBottom();
            });
        });
    </script>
</x-filament-panels::page>