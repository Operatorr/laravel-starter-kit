<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="garden">

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

<body class="min-h-screen bg-base-100">
	<div class="navbar bg-base-200">
		<div class="navbar-start">
			<h1 class="btn btn-ghost text-xl">Laravel Starter</h1>
		</div>
		<div class="navbar-end">
			<div x-data="themeSwitcher()" x-init="init()">
				<div class="dropdown dropdown-end">
					<div tabindex="0" role="button" class="btn btn-ghost">
						<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5H9m12 0v6m0 6v6M9 5v6m0 6h12"></path>
						</svg>
						Theme
					</div>
					<ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
						<template x-for="theme in themes" :key="theme.name">
							<li>
								<a @click="setTheme(theme.name)" 
								   :class="{'active': currentTheme === theme.name}"
								   x-text="theme.label"></a>
							</li>
						</template>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="card bg-base-100 shadow-xl m-4">
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

	<div class="card bg-base-100 shadow-xl m-4">
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

	<div class="card bg-base-100 shadow-xl m-4">
		<div class="card-body" x-data="chat">
			<h2 class="card-title">Chat Component</h2>
			<div class="chat-container max-h-60 overflow-y-auto mb-4">
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
			<div class="card-actions justify-end mt-4">
				<button @click="send" :disabled="loading" class="btn btn-primary" :class="{'loading': loading}">
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

		function themeSwitcher() {
			return {
				currentTheme: localStorage.getItem('theme') || 'garden',
				themes: [
					{ name: 'garden', label: 'Garden' },
					{ name: 'sunset', label: 'Sunset' }
				],

				init() {
					this.setTheme(this.currentTheme)
				},

				setTheme(theme) {
					this.currentTheme = theme
					document.documentElement.setAttribute('data-theme', theme)
					localStorage.setItem('theme', theme)
				}
			}
		}
	</script>

</body>

</html>
