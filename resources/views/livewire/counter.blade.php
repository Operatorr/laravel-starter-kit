<div class="card bg-base-100 h-full shadow-sm">
	<div class="card-body">
		<h2 class="card-title">Livewire Counter</h2>
		<div class="flex h-full items-center justify-center gap-4">
			<button wire:click="decrement" class="btn btn-circle btn-outline">
				<span class="text-xl">–</span>
			</button>

			<div class="badge badge-lg badge-neutral px-6 py-4 text-2xl font-bold">
				{{ $count }}
			</div>

			<button wire:click="increment" class="btn btn-circle btn-outline">
				<span class="text-xl">+</span>
			</button>
		</div>
	</div>
</div>
