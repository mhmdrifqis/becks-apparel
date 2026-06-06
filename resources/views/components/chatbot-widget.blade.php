<div 
    x-data="{ isOpen: false }" 
    class="fixed bottom-6 right-6 z-50 flex flex-col items-end"
>
    <!-- Chat Window -->
    <div 
        x-show="isOpen" 
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-10 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-10 scale-95"
        class="bg-white dark:bg-zinc-900 w-80 sm:w-96 rounded-2xl shadow-2xl border border-gray-200 dark:border-zinc-800 flex flex-col overflow-hidden mb-4"
        style="height: 500px; max-height: 80vh; display: none;"
    >
        <!-- Header -->
        <div class="bg-brand-900 text-white p-4 flex justify-between items-center shadow-md z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm relative" id="header-avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <!-- Pulse indicator -->
                    <span id="admin-pulse" class="absolute -top-1 -right-1 flex h-3 w-3 hidden">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                </div>
                <div>
                    <h3 class="font-bold text-sm tracking-wide" id="header-title">Asisten Becks</h3>
                    <p class="text-xs text-brand-200 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-green-400"></span> <span id="header-status">Online</span>
                    </p>
                </div>
            </div>
            <button @click="isOpen = false" class="text-brand-200 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Chat Area -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-zinc-950/50">
            <!-- Welcome Message -->
            <div class="flex items-start gap-2 max-w-[85%]">
                <div class="w-8 h-8 rounded-full bg-brand-900 flex-shrink-0 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <div class="bg-white dark:bg-zinc-800 text-gray-800 dark:text-zinc-200 px-4 py-2.5 rounded-2xl rounded-tl-sm shadow-sm border border-gray-100 dark:border-zinc-700/50 text-sm">
                    Halo! Saya asisten virtual Becks Apparel. Ada yang bisa saya bantu hari ini? 😊
                </div>
            </div>
        </div>

        <!-- Typing Indicator (Hidden by default) -->
        <div id="chat-typing" class="hidden px-4 pb-4 bg-gray-50 dark:bg-zinc-950/50">
            <div class="flex items-start gap-2">
                <div class="w-8 h-8 rounded-full bg-brand-900 flex-shrink-0 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <div class="bg-white dark:bg-zinc-800 px-4 py-3 rounded-2xl rounded-tl-sm shadow-sm border border-gray-100 dark:border-zinc-700/50 flex gap-1.5 items-center">
                    <div class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full animate-bounce"></div>
                    <div class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.15s"></div>
                    <div class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.3s"></div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white dark:bg-zinc-900 border-t border-gray-200 dark:border-zinc-800">
            <form id="chat-form" class="flex items-center gap-2 relative">
                <input 
                    type="text" 
                    id="chat-input"
                    class="w-full bg-gray-100 dark:bg-zinc-800 text-gray-900 dark:text-zinc-100 border-transparent focus:border-brand-500 focus:bg-white dark:focus:bg-zinc-900 focus:ring-0 rounded-full pl-4 pr-12 py-2.5 text-sm transition-all"
                    placeholder="Ketik pesan..."
                    autocomplete="off"
                >
                <button 
                    type="submit" 
                    class="absolute right-1 w-8 h-8 rounded-full bg-brand-900 hover:bg-brand-800 text-white flex items-center justify-center transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                    id="chat-submit"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Toggle Button -->
    <button 
        @click="isOpen = !isOpen"
        class="w-14 h-14 rounded-full bg-brand-900 hover:bg-brand-800 text-white shadow-lg shadow-brand-900/30 flex items-center justify-center transition-all duration-300 hover:scale-105 focus:outline-none ring-4 ring-white dark:ring-zinc-950 group"
    >
        <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

<!-- Chatbot Logic -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatMessages = document.getElementById('chat-messages');
    const chatTyping = document.getElementById('chat-typing');
    const submitBtn = document.getElementById('chat-submit');

    // UI elements for Admin Mode changes
    const headerTitle = document.getElementById('header-title');
    const headerStatus = document.getElementById('header-status');
    const adminPulse = document.getElementById('admin-pulse');

    let isAdminMode = false;
    let pollInterval = null;
    let lastMessageId = 0;

    const scrollToBottom = () => {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    };

    const toggleAdminUI = (isActive) => {
        if (isActive) {
            headerTitle.textContent = 'Customer Support';
            headerStatus.textContent = 'Menunggu Balasan Admin...';
            adminPulse.classList.remove('hidden');
        } else {
            headerTitle.textContent = 'Asisten Becks';
            headerStatus.textContent = 'Online';
            adminPulse.classList.add('hidden');
        }
    };

    const startPolling = () => {
        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(async () => {
            try {
                const res = await fetch(`{{ route('chatbot.poll') }}?last_id=${lastMessageId}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                
                if (data.status === 'closed') {
                    // Chat ended by admin
                    isAdminMode = false;
                    clearInterval(pollInterval);
                    toggleAdminUI(false);
                    
                    // Add system message
                    addMessage('Sesi obrolan dengan admin telah berakhir. Jika Anda memiliki pertanyaan lain, silakan tanyakan pada saya. 😊', 'bot');
                } else if (data.status === 'active' && data.messages.length > 0) {
                    headerStatus.textContent = 'Admin Terhubung';
                    data.messages.forEach(msg => {
                        addMessage(msg.message, 'admin');
                        lastMessageId = Math.max(lastMessageId, msg.id);
                    });
                }
            } catch (error) {
                console.error('Polling error', error);
            }
        }, 3000);
    };

    const addMessage = (message, sender = 'user', options = null) => {
        const div = document.createElement('div');
        div.className = `flex items-start gap-2 max-w-[85%] ${sender === 'user' ? 'ml-auto flex-row-reverse' : ''} animate-fade-in-up`;
        
        let avatarHTML = '';
        if (sender === 'bot') {
            avatarHTML = `
                <div class="w-8 h-8 rounded-full bg-brand-900 flex-shrink-0 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
            `;
        } else if (sender === 'admin') {
            avatarHTML = `
                <div class="w-8 h-8 rounded-full bg-blue-600 flex-shrink-0 flex items-center justify-center font-bold text-white text-xs">
                    CS
                </div>
            `;
        } else {
            avatarHTML = `
                <div class="w-8 h-8 rounded-full bg-brand-100 dark:bg-zinc-800 border border-brand-200 dark:border-zinc-700 flex-shrink-0 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-brand-900 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            `;
        }

        let bubbleClasses = 'bg-white dark:bg-zinc-800 text-gray-800 dark:text-zinc-200 border border-gray-100 dark:border-zinc-700/50 rounded-tl-sm';
        
        if (sender === 'user') {
            bubbleClasses = 'bg-brand-900 text-white rounded-tr-sm';
        } else if (sender === 'admin') {
            bubbleClasses = 'bg-blue-600 text-white rounded-tl-sm shadow-md';
        }

        const formattedMessage = message.replace(/\n/g, '<br>');

        let optionsHTML = '';
        if (options && options.length > 0) {
            optionsHTML = '<div class="mt-3 flex flex-wrap gap-2">';
            options.forEach(opt => {
                optionsHTML += `<button class="chat-option-btn text-xs px-3 py-1.5 rounded-full border border-brand-500 text-brand-700 dark:text-brand-400 hover:bg-brand-500 hover:text-white transition-colors" data-value="${opt}">${opt}</button>`;
            });
            optionsHTML += '</div>';
        }

        div.innerHTML = `
            ${avatarHTML}
            <div class="flex flex-col ${sender === 'user' ? 'items-end' : 'items-start'}">
                <div class="px-4 py-2.5 rounded-2xl shadow-sm text-sm ${bubbleClasses}">
                    ${formattedMessage}
                    ${optionsHTML}
                </div>
            </div>
        `;

        chatMessages.appendChild(div);
        
        if (options && options.length > 0) {
            const btns = div.querySelectorAll('.chat-option-btn');
            btns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const val = this.getAttribute('data-value');
                    btns.forEach(b => {
                        b.disabled = true;
                        b.classList.add('opacity-50', 'cursor-not-allowed');
                    });
                    sendChatMessage(val);
                });
            });
        }

        scrollToBottom();
    };

    const sendChatMessage = async (message) => {
        if (!message || message.trim() === '') return;

        addMessage(message, 'user');
        chatInput.value = '';
        chatInput.disabled = true;
        submitBtn.disabled = true;
        
        // Show typing indicator only if communicating with bot
        if (!isAdminMode) chatTyping.classList.remove('hidden');
        scrollToBottom();

        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const response = await fetch('{{ route("chatbot.handle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            
            if (!isAdminMode) chatTyping.classList.add('hidden');

            if (response.ok) {
                if (data.status === 'handover' || data.handover === true) {
                    addMessage(data.message, 'bot');
                    isAdminMode = true;
                    toggleAdminUI(true);
                    startPolling();
                } else if (data.status === 'admin_mode') {
                    // Berada di dalam sesi admin. Pesan berhasil masuk ke database.
                    // Tidak ada balasan bot.
                } else if (data.message) {
                    addMessage(data.message, 'bot', data.options || null);
                }
            } else {
                addMessage(data.message || 'Maaf, terjadi kesalahan saat menghubungi asisten.', 'bot');
            }
        } catch (error) {
            console.error('Chatbot Error:', error);
            chatTyping.classList.add('hidden');
            addMessage('Maaf, asisten sedang offline atau koneksi terputus.', 'bot');
        } finally {
            chatInput.disabled = false;
            submitBtn.disabled = false;
            chatInput.focus();
            scrollToBottom();
        }
    };

    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        sendChatMessage(chatInput.value);
    });
});
</script>

<style>
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out forwards;
}
</style>
