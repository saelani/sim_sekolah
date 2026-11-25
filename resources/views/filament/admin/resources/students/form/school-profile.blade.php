<div class="space-y-2">
    @if ($schoolProfile && $schoolProfile->logo_path)
        <img src="{{ asset('storage/' . $schoolProfile->logo_path) }}" alt="Logo {{ $schoolProfile->school_name }}" class="h-12 w-12 rounded object-contain shadow-sm">
    @endif

    <div class="grid grid-cols-2 gap-x-4">
        <div>
            <dt class="text-xs font-semibold text-gray-500">Nama Sekolah</dt>
            <dd class="text-sm font-bold text-gray-900">{{ $schoolProfile->school_name ?? '—' }}</dd>
        </div>
        <div>
            <dt class="text-xs font-semibold text-gray-500">NPSN</dt>
            <dd class="text-sm text-gray-900">{{ $schoolProfile->npsn ?? '—' }}</dd>
        </div>
        <div>
            <dt class="text-xs font-semibold text-gray-500">Kepala Sekolah</dt>
            <dd class="text-sm text-gray-900">{{ $schoolProfile->headmaster_name ?? '—' }}</dd>
        </div>
        <div>
            <dt class="text-xs font-semibold text-gray-500">Akreditasi</dt>
            <dd class="text-sm font-bold {{ $schoolProfile->accreditation === 'A' ? 'text-green-600' : ($schoolProfile->accreditation === 'B' ? 'text-blue-600' : 'text-amber-600') }}">{{ $schoolProfile->accreditation ?? '—' }}</dd>
        </div>
        <div>
            <dt class="text-xs font-semibold text-gray-500">Alamat</dt>
            <dd class="text-sm text-gray-900 line-clamp-2">{{ $schoolProfile->address ?? '—' }}</dd>
        </div>
        <div>
            <dt class="text-xs font-semibold text-gray-500">Telepon</dt>
            <dd class="text-sm text-gray-900">{{ $schoolProfile->phone ?? '—' }}</dd>
        </div>
        <div>
            <dt class="text-xs font-semibold text-gray-500">Email</dt>
            <dd class="text-sm text-gray-900 line-clamp-1">{{ $schoolProfile->email ?? '—' }}</dd>
        </div>
        <div>
            <dt class="text-xs font-semibold text-gray-500">Website</dt>
            <dd class="text-sm text-gray-900 line-clamp-1">{{ $schoolProfile->website ?? '—' }}</dd>
        </div>
    </div>
</div>
