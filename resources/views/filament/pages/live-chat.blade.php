<x-filament-panels::page>
    <div wire:poll.3s="loadActiveChats" class="grid grid-cols-1 md:grid-cols-3 gap-6 h-[70vh]">
        
        <!-- Sidebar: Active Chats -->
        <div class="col-span-1 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 flex flex-col overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                <h3 class="font-bold text-gray-800 dark:text-gray-100">Obrolan Aktif ({{ count($activeChats) }})</h3>
            </div>
            <div class="flex-1 overflow-y-auto p-2">
                @forelse($activeChats as $chat)
                    <button 
                        wire:click="selectChat({{ $chat->id }})"
                        class="w-full text-left p-3 mb-2 rounded-lg transition-colors border {{ $selectedChatId == $chat->id ? 'bg-primary-50 border-primary-500 dark:bg-primary-900/20 dark:border-primary-500' : 'bg-transparent border-transparent hover:bg-gray-50 dark:hover:bg-gray-800' }}"
                    >
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $chat->user_name ?? 'Guest User' }}</span>
                            <span class="text-xs text-gray-500">{{ $chat->created_at->format('H:i') }}</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1 truncate">ID: {{ substr($chat->user_id, 0, 15) }}...</div>
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
                <!-- Header -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 dark:text-gray-100">
                        Chat dengan Pelanggan
                    </h3>
                    <x-filament::button wire:click="endSession" color="danger" size="sm" icon="heroicon-o-x-circle">
                        Akhiri Sesi
                    </x-filament::button>
                </div>

                <!-- Messages -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-box">
                    @forelse($messages as $msg)
                        @if($msg->sender == 'admin')
                            <div class="flex items-start gap-3 flex-row-reverse">
                                <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center flex-shrink-0 text-white font-bold text-xs">CS</div>
                                <div class="bg-primary-600 text-white px-4 py-2 rounded-2xl rounded-tr-none shadow-sm text-sm max-w-[80%]">
                                    {{ $msg->message }}
                                </div>
                            </div>
                        @else
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 text-gray-600 dark:text-gray-300 font-bold text-xs">U</div>
                                <div class="bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-2xl rounded-tl-none border border-gray-200 dark:border-gray-700 shadow-sm text-sm max-w-[80%]">
                                    {{ $msg->message }}
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center text-sm text-gray-500">Belum ada pesan.</div>
                    @endforelse
                </div>

                <!-- Input -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                    <form wire:submit.prevent="sendMessage" class="flex gap-2">
                        <x-filament::input.wrapper class="flex-1">
                            <x-filament::input 
                                type="text" 
                                wire:model="newMessage" 
                                placeholder="Tulis balasan..." 
                                required 
                            />
                        </x-filament::input.wrapper>
                        <x-filament::button type="submit" color="primary">
                            Kirim
                        </x-filament::button>
                    </form>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-gray-500">
                    <svg class="w-16 h-16 mb-4 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p>Pilih salah satu obrolan di samping untuk mulai membalas.</p>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
