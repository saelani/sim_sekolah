<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
@php
    $description = $description ?? null;

    // 1. Deteksi panel Filament aktif atau fallback ke panel 'admin'
    $panel = filament()->getCurrentPanel() ?? filament()->getPanel('admin', isStrict: false);

    // 2. Tentukan URL Dashboard dengan aman
    $dashboardUrl = $panel?->getUrl()
        ?? (\Illuminate\Support\Facades\Route::has('dashboard') ? route('dashboard') : url('/'));

    // 3. Tentukan URL Login dengan aman (Filament panel login -> route('login') -> '/')
    if ($panel?->hasLogin()) {
        $loginUrl = $panel->getLoginUrl();
    } elseif (\Illuminate\Support\Facades\Route::has('login')) {
        $loginUrl = route('login');
    } else {
        $loginUrl = url('/');
    }

    // 4. Pastikan tombol "Kembali" mengarah ke URL internal yang valid jika ada
    $previousUrl = url()->previous();
    $currentUrl = url()->current();
    $isSafePrevious = filled($previousUrl)
        && $previousUrl !== $currentUrl
        && \Illuminate\Support\Str::startsWith($previousUrl, url('/'));

    $backUrl = $isSafePrevious ? $previousUrl : (auth()->check() ? $dashboardUrl : $loginUrl);
@endphp
<body class="antialiased bg-gradient-to-b from-slate-50 to-slate-100 text-slate-800">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md bg-white border border-slate-200/80 shadow-xl rounded-2xl p-8 text-center">
            <!-- Badge & Ikon -->
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-red-50 border border-red-100">
                <svg class="w-9 h-9 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                </svg>
            </div>

            <span class="inline-block mt-5 px-3 py-1 text-xs font-semibold tracking-wider uppercase rounded-full bg-red-50 text-red-600 border border-red-200/70">
                Error 403
            </span>

            <h1 class="text-2xl font-bold tracking-tight text-slate-900 mt-3">
                Akses Ditolak
            </h1>

            <p class="text-slate-600 mt-2 text-sm leading-relaxed">
                {{ $message ?? 'Halaman yang anda tuju tidak diizinkan diakses disini.' }}
            </p>

            @if(filled($description))
                <div class="mt-4 rounded-xl bg-slate-50 border border-slate-200/80 px-4 py-3 text-left">
                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed">
                        {{ $description }}
                    </p>
                </div>
            @endif

            <!-- Tombol Aksi -->
            <footer class="mt-7 flex flex-wrap items-center justify-center gap-3">
                <a
                    href="{{ $backUrl }}"
                    class="inline-flex items-center justify-center px-4 py-2.5 bg-white text-slate-700 border border-slate-300 text-sm font-medium rounded-xl shadow-sm hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2 transition-all"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>

                @auth
                    <a
                        href="{{ $dashboardUrl }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 text-white text-sm font-medium rounded-xl shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                @else
                    <a
                        href="{{ $loginUrl }}"
                        class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 text-white text-sm font-medium rounded-xl shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Login
                    </a>
                @endauth
            </footer>
        </div>
    </div>
</body>
</html>