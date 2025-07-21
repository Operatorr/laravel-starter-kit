<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>{{ $title ?? 'Laravel' }}</title>

	<link rel="icon" href="/favicon.ico" sizes="any">
	<link rel="icon" href="/favicon.svg" type="image/svg+xml">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	@livewireStyles
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	
	<script>
		// Theme management - global variable in head to persist across navigations
		window.themeState = window.themeState || {
			preservedTheme: null
		};
	</script>
</head>

<body class="bg-base-100 min-h-screen">
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
					<li><a href="#" onclick="changeTheme('garden')">🌻 Garden</a></li>
					<li><a href="#" onclick="changeTheme('sunset')">🌅 Sunset</a></li>
				</ul>
			</div>
		</div>
	</div>

	<main>
		@yield('content')
	</main>

	@livewireScriptConfig
	@stack('scripts')

	<script>
		// Theme management functions
		async function changeTheme(theme) {
			// Set theme immediately for better UX
			document.documentElement.setAttribute('data-theme', theme);
			localStorage.setItem('theme', theme);

			// Save to database if user is authenticated
			@auth
			try {
				const response = await fetch('{{ route('theme.update') }}', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-CSRF-TOKEN': '{{ csrf_token() }}'
					},
					body: JSON.stringify({ theme: theme })
				});

				if (!response.ok) {
					console.error('Failed to save theme to database');
				}
			} catch (error) {
				console.error('Error saving theme:', error);
			}
			@endauth
		}

		// Load theme on page load
		async function loadTheme(forceReload = false) {
			// If we have a preserved theme from navigation, use it first
			if (window.themeState.preservedTheme && !forceReload) {
				document.documentElement.setAttribute('data-theme', window.themeState.preservedTheme);
				return;
			}

			let theme = 'garden'; // default

			@auth
			// Get user's saved theme from database
			try {
				const response = await fetch('{{ route('theme.show') }}');
				if (response.ok) {
					const data = await response.json();
					theme = data.theme;
				}
			} catch (error) {
				console.error('Error loading theme:', error);
				// Fallback to localStorage
				theme = localStorage.getItem('theme') || 'garden';
			}
			@else
			// For non-authenticated users, use localStorage
			theme = localStorage.getItem('theme') || 'garden';
			@endauth

			document.documentElement.setAttribute('data-theme', theme);
			localStorage.setItem('theme', theme);
		}

		// Preserve theme before navigation
		document.addEventListener('livewire:navigating', function() {
			// Capture current theme before DOM replacement
			window.themeState.preservedTheme = document.documentElement.getAttribute('data-theme');
		});

		// Load theme on initial page load
		document.addEventListener('DOMContentLoaded', function() {
			loadTheme(true); // Force initial load from server/localStorage
		});
		
		// Restore/ensure theme after navigation
		document.addEventListener('livewire:navigated', function() {
			loadTheme(); // Will use preserved theme if available
		});
	</script>
</body>

</html>
