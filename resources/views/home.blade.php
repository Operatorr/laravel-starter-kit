@extends('layouts.app')

@section('content')
    <div class="card bg-base-100 m-4 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">Simple Counter</h2>
            <div x-data="counter">
                <p class="text-lg">Count: <span class="badge badge-primary" x-text="count"></span></p>
                <div class="card-actions justify-end">
                    <button x-on:click="addCount()" class="btn btn-primary">Add</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 m-4 shadow-xl">
        <div class="card-body">
            <h2 class="card-title">Toggle Component</h2>
            <div x-data="{ open: false }">
                <button @click="open = !open" class="btn btn-secondary">Toggle</button>

                <div x-show="open" x-transition class="alert alert-info mt-4">
                    <span>I'm alive!</span>
                </div>
            </div>
        </div>
    </div>

    <livewire:counter />

    <div class="card bg-base-100 m-4 shadow-xl">
        <div class="card-body" x-data="chat">
            <h2 class="card-title">Chat Component</h2>
            <div class="chat-container mb-4 max-h-60 overflow-y-auto">
                <template x-for="m in messages" :key="m.id">
                    <div class="chat" :class="m.role === 'user' ? 'chat-end' : 'chat-start'">
                        <div class="chat-header" x-text="m.role === 'user' ? 'You' : 'Bot'"></div>
                        <div class="chat-bubble" :class="m.role === 'user' ? 'chat-bubble-primary' : 'chat-bubble-secondary'" x-text="m.content"></div>
                    </div>
                </template>
            </div>

            <div class="form-control">
                <textarea x-model="prompt" class="textarea textarea-bordered" placeholder="Type your message..."></textarea>
            </div>
            <div class="card-actions mt-4 justify-end">
                <button @click="send" :disabled="loading" class="btn btn-primary" :class="{ 'loading': loading }">
                    <span x-show="!loading">Send</span>
                    <span x-show="loading">Loading…</span>
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function counter() {
            return {
                count: 0,
                addCount() {
                    this.count++;
                }
            }
        }

        function chat() {
            return {
                // --- reactive state --------------------------------------------------
                prompt: '',
                messages: [],
                loading: false,
                nextId: 0,

                // --- helpers ---------------------------------------------------------
                pushMessage(role, content = '') {
                    this.messages.push({
                        id: this.nextId++,
                        role,
                        content
                    })
                    // force reactivity if we mutate strings later
                    this.messages = [...this.messages]
                    return this.messages[this.messages.length - 1]
                },

                // --- main action -----------------------------------------------------
                async send() {
                    if (!this.prompt.trim() || this.loading) return
                    const userMsg = this.prompt.trim()

                    // 1. show the user's line immediately
                    this.pushMessage('user', userMsg)
                    this.prompt = ''
                    this.loading = true

                    // 2. call the endpoint
                    const res = await fetch('{{ route('chat.stream') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            messages: this.messages
                        })
                    })

                    // 3A. JSON response (your current controller)
                    if (res.headers.get('content-type')?.includes('application/json')) {
                        const {
                            message
                        } = await res.json()
                        this.pushMessage('assistant', message)

                        // 3B. text/stream fallback (future-proof)
                    } else {
                        const assistantRef = this.pushMessage('assistant', '')
                        const reader = res.body.getReader()
                        const decoder = new TextDecoder()

                        while (true) {
                            const {
                                done,
                                value
                            } = await reader.read()
                            if (done) break
                            assistantRef.content += decoder.decode(value, {
                                stream: true
                            })
                            // ping Alpine so the DOM updates
                            this.messages = [...this.messages]
                        }
                    }

                    this.loading = false
                }
            }
        }
    </script>
@endpush
