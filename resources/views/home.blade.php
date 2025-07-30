@extends('layouts.app')

@section('content')
	<div class="m-4 grid grid-cols-1 gap-4 lg:grid-cols-3">
		<div class="card bg-base-100 h-full shadow-sm">
			<div class="card-body">
				<h2 class="card-title">Simple Alpine Counter</h2>
				<div x-data="counter">
					<p class="text-lg">Count: <span class="badge badge-neutral" x-text="count"></span></p>
					<div class="card-actions justify-end">
						<button x-on:click="addCount()" class="btn btn-neutral">Add</button>
					</div>
				</div>
			</div>
		</div>

		<div class="card bg-base-100 h-full shadow-sm">
			<div class="card-body">
				<h2 class="card-title">Simple Alpine Toggle Component</h2>
				<div x-data="{ open: false }">
					<button @click="open = !open" class="btn btn-secondary">Toggle</button>

					<div x-show="open" x-transition class="alert alert-neutral mt-4">
						<span>I'm alive!</span>
					</div>
				</div>
			</div>
		</div>

		<livewire:counter />
	</div>
	@include('components.chat')
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
	</script>
@endpush
