<div class="card bg-base-100 shadow-xl m-4">
	<div class="card-body">
		<h2 class="card-title">Livewire Counter</h2>
		<div class="flex items-center justify-center gap-4">
			<button wire:click="decrement" class="btn btn-circle btn-outline">
				<span class="text-xl">–</span>
			</button>

			<div class="badge badge-lg badge-neutral text-2xl font-bold px-6 py-4">
				{{ $count }}
			</div>

			<button wire:click="increment" class="btn btn-circle btn-outline">
				<span class="text-xl">+</span>
			</button>
		</div>
	</div>
</div>
