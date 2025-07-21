<div class="p-4">
	<div class="inline-flex items-center gap-3">
		<button wire:click="decrement" class="rounded bg-gray-200 px-3 py-1 text-xl leading-none">–</button>

		<span class="min-w-[3ch] text-center text-2xl font-medium">
			{{ $count }}
		</span>

		<button wire:click="increment" class="rounded bg-gray-200 px-3 py-1 text-xl leading-none">+</button>
	</div>
</div>
