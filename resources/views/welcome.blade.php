<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Laravel</title>

	<link rel="icon" href="/favicon.ico" sizes="any">
	<link rel="icon" href="/favicon.svg" type="image/svg+xml">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	@livewireStyles
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-base-100 min-h-screen">
	<div class="navbar bg-base-200">
		<div class="navbar-start">
			<h1 class="btn btn-ghost text-xl">Laravel Starter</h1>
		</div>
		<div class="navbar-center">
			<ul class="menu bg-base-200 lg:menu-horizontal rounded-box">
				<li>
					<a href="/" wire:navigate>
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
						</svg>
						Home
					</a>
				</li>
				<li>
					<a href="/about" wire:navigate>
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
						</svg>
						About
					</a>
				</li>
				<li>
					<a href="/contact" wire:navigate>
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
						</svg>
						Contact
					</a>
				</li>
			</ul>
		</div>
		<div class="navbar-end">
			<div class="dropdown dropdown-end">
				<div tabindex="0" role="button" class="btn btn-ghost">
					<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5H9m12 0v6m0 6v6M9 5v6m0 6h12"></path>
					</svg>
					Theme
				</div>
				<ul tabindex="0" class="dropdown-content z-1 menu bg-base-100 rounded-box w-52 p-2 shadow">
					<li><a data-set-theme="garden" data-act-class="ACTIVECLASS">🌻 Garden</a></li>
					<li><a data-set-theme="sunset" data-act-class="ACTIVECLASS">🌅 Sunset</a></li>
				</ul>
			</div>
		</div>
	</div>
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


	@livewireScriptConfig
	<script>
		function counter() {
			return {
				count: 0,
				addCount() {
					this.count++;
				}
			}
		}
	</script>

	<script>
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

</body>

</html>
