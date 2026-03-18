<x-layouts.app title="AI Assistant">
    <x-slot:header>AI Assistant</x-slot:header>

    <div x-data="aiChat()" x-init="init()" class="flex gap-4 h-[calc(100vh-8rem)]">
        {{-- Left: File context --}}
        <div class="w-56 shrink-0 hidden lg:block overflow-y-auto">
            <div class="bg-white border border-border rounded-lg p-3">
                <p class="text-xs font-semibold text-secondary uppercase tracking-wider mb-2">Context Files</p>
                @if($folders->count())
                    @foreach($folders as $folder)
                        <div class="mb-2">
                            <p class="text-xs font-medium text-primary flex items-center gap-1">
                                <svg class="w-3 h-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                {{ $folder->name }}
                            </p>
                        </div>
                    @endforeach
                @endif
                @foreach($files as $file)
                    <label class="flex items-center gap-2 py-1 cursor-pointer hover:bg-gray-50 rounded px-1">
                        <input type="checkbox" :checked="contextFiles.includes({{ $file->id }})" @change="toggleFile({{ $file->id }})" class="rounded border-gray-300 text-accent focus:ring-accent w-3 h-3">
                        <span class="text-xs text-secondary truncate">{{ $file->original_name }}</span>
                    </label>
                @endforeach
                @if($files->isEmpty())
                    <p class="text-xs text-secondary">No files available.</p>
                @endif
            </div>
        </div>

        {{-- Center: Chat --}}
        <div class="flex-1 flex flex-col bg-white border border-border rounded-lg overflow-hidden">
            {{-- Messages --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-messages" x-ref="messages">
                <template x-if="messages.length === 0">
                    <div class="flex flex-col items-center justify-center h-full text-center">
                        <div class="w-12 h-12 bg-accent/10 rounded-xl flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <p class="text-sm font-medium text-primary">ResearchFlow AI</p>
                        <p class="text-xs text-secondary mt-1 max-w-sm">Ask me about your research, get help with writing, methodology, or deadline planning.</p>
                    </div>
                </template>

                <template x-for="msg in messages" :key="msg.id">
                    <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                        <div :class="msg.role === 'user' ? 'bg-primary text-white rounded-2xl rounded-br-md max-w-md' : 'bg-gray-50 text-primary rounded-2xl rounded-bl-md max-w-lg'" class="px-4 py-2.5 text-sm">
                            <div x-html="formatMessage(msg.content)"></div>
                        </div>
                    </div>
                </template>

                <template x-if="loading">
                    <div class="flex justify-start">
                        <div class="bg-gray-50 rounded-2xl rounded-bl-md px-4 py-3">
                            <div class="flex gap-1">
                                <div class="w-2 h-2 bg-gray-300 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                                <div class="w-2 h-2 bg-gray-300 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                                <div class="w-2 h-2 bg-gray-300 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Input --}}
            <div class="border-t border-border p-3">
                <form @submit.prevent="send()" class="flex gap-2">
                    <input x-model="input" type="text" placeholder="Ask ResearchFlow AI..." class="flex-1 rounded-lg border border-border px-3 py-2 text-sm focus:border-accent focus:ring-1 focus:ring-accent/30 outline-none" :disabled="loading">
                    <button type="submit" :disabled="loading || !input.trim()" class="bg-accent text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-amber-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        Send
                    </button>
                </form>
            </div>
        </div>

        {{-- Right: Suggestions --}}
        <div class="w-56 shrink-0 hidden xl:block overflow-y-auto">
            <div class="bg-white border border-border rounded-lg p-3">
                <p class="text-xs font-semibold text-secondary uppercase tracking-wider mb-2">Quick Actions</p>
                <div class="space-y-1.5">
                    <button @click="input = 'Summarize my latest progress report'; send()" class="w-full text-left text-xs text-secondary hover:text-accent hover:bg-gray-50 rounded px-2 py-1.5 transition-colors">Summarize latest report</button>
                    <button @click="input = 'What tasks are at risk of missing their deadline?'; send()" class="w-full text-left text-xs text-secondary hover:text-accent hover:bg-gray-50 rounded px-2 py-1.5 transition-colors">Check deadline risks</button>
                    <button @click="input = 'Suggest next steps for my research'; send()" class="w-full text-left text-xs text-secondary hover:text-accent hover:bg-gray-50 rounded px-2 py-1.5 transition-colors">Suggest next tasks</button>
                    <button @click="input = 'Help me write a literature review outline'; send()" class="w-full text-left text-xs text-secondary hover:text-accent hover:bg-gray-50 rounded px-2 py-1.5 transition-colors">Literature review help</button>
                    <button @click="input = 'Review my research methodology'; send()" class="w-full text-left text-xs text-secondary hover:text-accent hover:bg-gray-50 rounded px-2 py-1.5 transition-colors">Review methodology</button>
                </div>

                <div class="mt-4 pt-3 border-t border-border">
                    <p class="text-xs font-semibold text-secondary uppercase tracking-wider mb-2">Conversations</p>
                    <template x-for="conv in conversations" :key="conv.id">
                        <button @click="loadConversation(conv.id)" class="w-full text-left text-xs text-secondary hover:text-accent hover:bg-gray-50 rounded px-2 py-1.5 truncate" x-text="conv.title"></button>
                    </template>
                    <button @click="newConversation()" class="w-full text-left text-xs text-accent hover:underline px-2 py-1.5">+ New conversation</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function aiChat() {
            return {
                input: '',
                messages: [],
                conversations: [],
                currentConversation: null,
                contextFiles: [],
                loading: false,

                async init() {
                    try {
                        const res = await axios.get('/api/ai/conversations');
                        this.conversations = res.data;
                    } catch(e) {}
                },

                toggleFile(id) {
                    const idx = this.contextFiles.indexOf(id);
                    idx > -1 ? this.contextFiles.splice(idx, 1) : this.contextFiles.push(id);
                },

                async newConversation() {
                    this.messages = [];
                    this.currentConversation = null;
                },

                async loadConversation(id) {
                    try {
                        const res = await axios.get(`/api/ai/conversations/${id}/messages`);
                        this.messages = res.data;
                        this.currentConversation = id;
                        this.$nextTick(() => this.scrollToBottom());
                    } catch(e) {}
                },

                async send() {
                    if (!this.input.trim() || this.loading) return;
                    const content = this.input;
                    this.input = '';

                    // Create conversation if needed
                    if (!this.currentConversation) {
                        try {
                            const res = await axios.post('/api/ai/conversations', {
                                title: content.substring(0, 50),
                                student_id: {{ $student?->id ?? 'null' }},
                                context_files: this.contextFiles,
                                scope: '{{ $student ? "student" : "general" }}'
                            });
                            this.currentConversation = res.data.id;
                            this.conversations.unshift(res.data);
                        } catch(e) { return; }
                    }

                    this.messages.push({ id: Date.now(), role: 'user', content });
                    this.loading = true;
                    this.scrollToBottom();

                    try {
                        const res = await axios.post(`/api/ai/conversations/${this.currentConversation}/messages`, { content });
                        this.messages = res.data.messages;
                    } catch(e) {
                        this.messages.push({ id: Date.now(), role: 'assistant', content: 'Sorry, something went wrong.' });
                    }

                    this.loading = false;
                    this.scrollToBottom();
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const el = this.$refs.messages;
                        if (el) el.scrollTop = el.scrollHeight;
                    });
                },

                formatMessage(text) {
                    return text.replace(/\n/g, '<br>');
                }
            };
        }
    </script>
    @endpush
</x-layouts.app>
