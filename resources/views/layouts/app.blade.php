<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>{{ $title ?? 'Laravel Starter' }}</title>

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

<body class="bg-base-200 min-h-screen">
	@include('layouts.nav')
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
					body: JSON.stringify({
						theme: theme
					})
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

			let theme = 'pastel'; // default

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
				theme = localStorage.getItem('theme') || 'pastel';
			}
		@else
			// For non-authenticated users, use localStorage
			theme = localStorage.getItem('theme') || 'pastel';
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
