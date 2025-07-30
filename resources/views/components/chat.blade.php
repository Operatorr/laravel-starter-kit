<div class="card bg-base-100 m-4 shadow-sm">
	<div class="card-body relative flex min-h-80 flex-col" x-data="chat">
		<h2 class="card-title">Chat Component</h2>

		<!---- Message stream ---->
		<div id="messages" x-ref="stream" class="flex-1 space-y-4 overflow-y-auto px-4 py-6">
			<template x-for="msg in messages" :key="msg.id">
				<div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
					<div :class="[
					    'max-w-[75%] p-3 rounded',
					    msg.role === 'user' ?
					    'bg-primary text-primary-content' :
					    'bg-base-200 text-base-content',
					    // add typography styling if the plugin is installed
					    'prose', // remove these two if you skip the plugin
					    'prose-invert'
					]" x-html="md2html(msg.content)"></div>
				</div>
			</template>
			<div x-ref="bottom"></div>
		</div>

		<!---- Centered input before first message ---->
		<div x-show="messages.length === 0" x-transition x-cloak class="absolute inset-0 flex flex-col items-center justify-center">
			<h1 class="mb-4 text-2xl font-light">
				<svg class="text-primary mr-2 inline" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
					<path fill="currentColor"
						d="M21.488 8.511a3.5 3.5 0 0 1 .837 1.363l.548 1.682a.664.664 0 0 0 1.254 0l.548-1.682a3.47 3.47 0 0 1 2.197-2.196l1.684-.547a.665.665 0 0 0 0-1.254l-.034-.008l-1.683-.547a3.47 3.47 0 0 1-2.198-2.196l-.547-1.682a.665.665 0 0 0-1.255 0l-.547 1.682l-.014.042a3.47 3.47 0 0 1-2.15 2.154l-1.684.547a.665.665 0 0 0 0 1.254l1.684.546c.513.171.979.46 1.36.842m9.333 4.847l.918.298l.019.004a.362.362 0 0 1 0 .684l-.919.299a1.9 1.9 0 0 0-1.198 1.197l-.299.918a.363.363 0 0 1-.684 0l-.299-.918a1.89 1.89 0 0 0-1.198-1.202l-.919-.298a.362.362 0 0 1 0-.684l.919-.299a1.9 1.9 0 0 0 1.18-1.197l.299-.918a.363.363 0 0 1 .684 0l.298.918a1.89 1.89 0 0 0 1.199 1.197M16.001 2c1.68 0 3.293.296 4.787.84a2.1 2.1 0 0 1-.388.56a1.86 1.86 0 0 1-.76.48l-1.125.384A12 12 0 0 0 16 4C9.373 4 4 9.373 4 16c0 2.165.572 4.193 1.573 5.945a1 1 0 0 1 .094.77l-1.439 5.059l5.061-1.44a1 1 0 0 1 .77.094A11.94 11.94 0 0 0 16 28c5.935 0 10.863-4.308 11.829-9.968q.063.057.131.108a1.89 1.89 0 0 0 1.85.175C28.706 24.945 22.944 30 16 30c-2.368 0-4.602-.589-6.56-1.629l-5.528 1.572A1.5 1.5 0 0 1 2.06 28.09l1.572-5.527A13.94 13.94 0 0 1 2 16C2 8.268 8.267 2 16 2" />
				</svg>
				How may I serve?
			</h1>
			<form @submit.prevent="send" class="relative w-full max-w-2xl">
				<textarea x-model="prompt" rows="1" @input="resize($event)" @keydown.enter="if(!$event.shiftKey){$event.preventDefault(); send();}" class="border-base-300 bg-base-200 focus:ring-primary/40 w-full resize-none overflow-y-scroll rounded border p-3 pr-14 outline-none focus:ring-2" placeholder="State your need."></textarea>
				<button type="submit" class="bg-primary absolute bottom-3 right-3 flex h-9 w-9 items-center justify-center rounded-full text-white disabled:opacity-50" :disabled="loading">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 -rotate-90">
						<path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" />
					</svg>
				</button>
			</form>
		</div>

		<!---- Docked input after first exchange ---->
		<div x-show="messages.length > 0" x-transition x-cloak class="sticky bottom-0 w-full px-4 py-3 backdrop-blur">
			<form @submit.prevent="send" class="relative">
				<textarea x-model="prompt" rows="1" @input="resize($event)" @keydown.enter="if(!$event.shiftKey){$event.preventDefault(); send();}" class="border-base-300 focus:ring-primary/40 bg-base-200 w-full resize-none overflow-hidden rounded border p-3 pr-14 outline-none focus:ring-2" placeholder="Speak your purpose..."></textarea>
				<button type="submit" class="bg-primary/70 absolute bottom-3 right-2 flex h-9 w-9 items-center justify-center rounded-full text-white disabled:opacity-50" :disabled="loading">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 -rotate-90">
						<path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" />
					</svg>
				</button>
			</form>
		</div>
	</div>
</div>

@push('scripts')
	<script>
		function chat() {
			return {
				prompt: '',
				messages: [],
				loading: false,
				systemPrompt: '',
				selectedModel: null,

				// Use the global md2html function from app.js
				md2html(text) {
					if (!text) return '';
					return window.md2html ? window.md2html(text) : text;
				},

				/** Auto-scroll whenever messages change */
				scroll() {
					this.$nextTick(() => {
						if (this.$refs.bottom) {
							this.$refs.bottom.scrollIntoView({
								behavior: 'smooth',
								block: 'start',
							});
						}
					});
				},

				resize(e) {
					// grow up to 10 rows
					const lineHeight = parseInt(getComputedStyle(e.target).lineHeight) || 20;
					const maxHeight = lineHeight * 10; // ≈ ten rows
					e.target.style.height = 'auto';
					e.target.style.height = Math.min(e.target.scrollHeight, maxHeight) + 'px';
				},

				async send() {
					if (!this.prompt.trim()) return;

					/* 1️⃣  add the user message to history */
					this.messages.push({
						id: Date.now(),
						role: 'user',
						content: this.prompt
					});

					/* 2️⃣  prepare a placeholder for the streaming reply */
					const myId = Date.now() + 1;
					this.messages.push({
						id: myId,
						role: 'assistant',
						content: ''
					});
					const assistantRef = this.messages.find(m => m.id === myId);

					/* 3️⃣  build a clean array for the API  */
					const payload = this.messages
						.filter(m => m.content !== '') // skip empty placeholder
						.map(({
							role,
							content
						}) => ({
							role,
							content
						})); // drop frontend-only props

					this.loading = true;

					try {
						const res = await fetch('{{ route('chat.stream') }}', {
							method: 'POST',
							headers: {
								'Content-Type': 'application/json',
								'X-CSRF-TOKEN': '{{ csrf_token() }}'
							},
							body: JSON.stringify({
								model: this.selectedModel,
								messages: payload,
								systemPrompt: this.systemPrompt
							}),
						});

						const reader = res.body.getReader();
						const decoder = new TextDecoder();

						while (true) {
							const {
								done,
								value
							} = await reader.read();
							if (done) break;
							assistantRef.content += decoder.decode(value);
							this.messages = [...this.messages]; // trigger Alpine reactivity
							this.scroll();
						}
					} catch (error) {
						assistantRef.content = 'Error: ' + error.message;
					}

					this.prompt = '';
					this.loading = false;
					this.scroll();

					/* reset textarea height back to one row */
					this.$nextTick(() => {
						document.querySelectorAll('textarea').forEach(t => {
							t.style.height = 'auto';
						});
					});
				}
			}
		}
	</script>
@endpush
