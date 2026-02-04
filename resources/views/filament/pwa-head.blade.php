<!-- PWA Manifest -->
<link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
<meta name="theme-color" content="#10b981">
<link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

<!-- Load Vite Scripts (Start Service Worker) -->
@vite('resources/js/app.js')
