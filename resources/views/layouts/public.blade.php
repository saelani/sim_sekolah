<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Sekolah'))</title>

    {{-- Google Font Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- CSS Utama Portal Publik --}}
    @if (file_exists(public_path('css/public-portal.css')))
        <link rel="stylesheet" href="{{ asset('css/public-portal.css') }}?v={{ filemtime(public_path('css/public-portal.css')) }}">
    @endif

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary: #1a3a5c;
            --primary-light: #2c5f8a;
            --accent: #c59b27;
            --bg: #f8f7f4;
            --card: #ffffff;
            --text: #1e293b;
            --text-light: #64748b;
            --border: #e6e2d8;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: var(--text);
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        main.public-main {
            flex: 1;
            width: 100%;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        img {
            max-width: 100%;
            display: block;
        }

        ul {
            list-style: none;
        }

        /* Container & Komponen Dasar (Kompatibel untuk Semua Halaman) */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e6e2d8;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 4px 16px rgba(26, 58, 92, 0.04);
        }

        .card-title {
            font-size: 1.08rem;
            font-weight: 700;
            color: #1a3a5c;
            margin-bottom: 6px;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1a3a5c;
            margin: 0 0 8px;
        }

        .section-sub {
            color: #64748b;
            margin-bottom: 24px;
        }

        .empty {
            color: #64748b;
            text-align: center;
            padding: 36px 0;
        }

        /* Footer Utama */
        .footer {
            background: linear-gradient(135deg, #132c47 0%, #1a3a5c 100%);
            color: rgba(255, 255, 255, 0.9);
            border-top: 3px solid #c59b27;
            padding: 32px 20px;
            text-align: center;
            font-size: 0.86rem;
            margin-top: auto;
        }

        .footer span {
            color: #f8e5a8;
            font-weight: 600;
        }

        /* Breadcrumb & Navigasi Kembali */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #1a3a5c;
            font-weight: 600;
            font-size: 0.88rem;
            margin-bottom: 16px;
            transition: color 0.15s ease;
        }

        .back-link:hover {
            color: #c59b27;
            text-decoration: underline;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            font-size: 0.84rem;
            color: #64748b;
            margin: 16px 0;
        }

        .breadcrumb a {
            color: #1a3a5c;
            font-weight: 600;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb span {
            color: #1e293b;
        }

        /* Utility Helpers (Untuk Halaman Detail / Pendukung) */
        .bg-primary { background: #1a3a5c; }
        .text-primary { color: #1a3a5c; }
        .border-primary { border-color: #1a3a5c; }
        .shadow_card { box-shadow: 0 6px 20px rgba(26, 58, 92, 0.10); }
        .bg-light { background: #f8f7f4; }
        .border-light { border-color: #e6e2d8; }
        .bg-card { background: #ffffff; }
        .text-light { color: #64748b; }
        .text-accent { color: #c59b27; }
        .d-flex, .flex { display: flex; }
        .mt-1 { margin-top: 6px; }
        .mb-1 { margin-bottom: 6px; }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }
        .mb-3 { margin-bottom: 12px; }
        .mb-4 { margin-bottom: 16px; }
        .pt-3 { padding-top: 16px; }
        .text-sm { font-size: 0.85rem; }
        .text-xs { font-size: 0.75rem; }
        .text-2xl { font-size: 1.5rem; }
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .w-full { width: 100%; }
        .w-16 { width: 4rem; }
        .h-32 { height: 8rem; }
        .h-40 { height: 10rem; }
        .h-72 { height: 18rem; }
        .object-cover { object-fit: cover; }
        .object-contain { object-fit: contain; }
        .rounded-md { border-radius: 8px; }
        .rounded-full { border-radius: 50%; }
        .shadow-lg { box-shadow: 0 8px 24px rgba(26, 58, 92, 0.12); }
        .inline-block { display: inline-block; }
        .px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
        .py-1 { padding-top: 0.25rem; padding-bottom: 0.25rem; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
        .gap-4 { gap: 1rem; }
        .gap-3 { gap: 0.75rem; }
        .gap-2 { gap: 0.5rem; }
        .gap-1 { gap: 0.25rem; }
        .items-center { align-items: center; }
        .items-start { align-items: flex-start; }
        .justify-between { justify-content: space-between; }
        .justify-center { justify-content: center; }
    </style>

    @yield('page-style')
</head>
<body>
    @include('components.public.header')

    <main class="public-main">
        @yield('content')
    </main>

    @include('components.public.footer')
</body>
</html>