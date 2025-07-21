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

<body class="">
	<h1 class="bg-gray-50 p-4 text-xl text-zinc-500">Tailwind is Working</h1>
	<div class="p-4" x-data="counter">
		Simple Counter
		<p>Count: <span x-text="count"></span></p>

		<button x-on:click="addCount()" class="cursor-pointer bg-gray-100 p-2">Add</button>
	</div>

	<div class="p-4">
		<div x-data="{ open: false }">
			<button @click="open = !open" class="cursor-pointer bg-gray-100 p-2">Toggle</button>

			<div x-show="open" x-transition class="mt-1 rounded bg-slate-100 p-4">
				I’m alive!
			</div>
		</div>
	</div>

	<livewire:counter />

	<div class="p-4" x-data="chat">
		<template x-for="m in messages" :key="m.id">
			<p>
				<strong x-text="m.role === 'user' ? 'You:' : 'Bot:'"></strong>
				<span x-text="m.content"></span>
			</p>
		</template>

		<textarea x-model="prompt" class="w-full rounded border"></textarea>

		<button @click="send" :disabled="loading" class="rounded bg-blue-600 px-4 py-2 text-white disabled:opacity-50">
			<span x-show="!loading">Send</span>
			<span x-show="loading">Loading…</span>
		</button>
	</div>

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
	@livewireScriptConfig

</body>

</html>
