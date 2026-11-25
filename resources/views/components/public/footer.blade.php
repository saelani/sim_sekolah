@php
    $profile = $profile ?? \App\Models\SchoolProfile::first();
    $schoolName = $profile->school_name ?? 'Sekolah';
@endphp

<footer class="footer">
    <div class="container">
        <p>&copy; {{ date('Y') }} <span>{{ $schoolName }}</span>. All rights reserved.</p>
        <p class="text-sm mt-1" style="opacity:.7;">
            {{ $profile->address ?? '' }} &middot; {{ $profile->phone ?? '' }} &middot; {{ $profile->email ?? '' }}
        </p>
    </div>
</footer>
