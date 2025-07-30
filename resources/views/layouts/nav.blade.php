<div class="navbar bg-base-200">
	<div class="navbar-start">
		<h1 class="btn btn-ghost text-xl">Laravel Starter</h1>
		@auth
			<div class="ml-4">
				<span class="text-sm opacity-70">Welcome,</span>
				<span class="font-semibold">{{ Auth::user()->name }}</span>
			</div>
		@endauth
	</div>
	<div class="navbar-center">
		<ul class="menu bg-base-200 lg:menu-horizontal rounded-box">
			<li>
				<a href="/" wire:navigate>
					{{-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
					</svg> --}}
					Home
				</a>
			</li>
			<li>
				<a href="/about" wire:navigate>
					{{-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
					</svg> --}}
					About
				</a>
			</li>
			<li>
				<a href="/contact" wire:navigate>
					{{-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
					</svg> --}}
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
				<li><a href="#" onclick="changeTheme('pastel')">🦄 Pastel</a></li>
				<li><a href="#" onclick="changeTheme('synthwave')">🌃 Synthwave</a></li>
			</ul>
		</div>
	</div>
</div>
