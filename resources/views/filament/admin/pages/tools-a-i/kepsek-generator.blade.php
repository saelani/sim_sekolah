<x-filament-panels::page>
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .print-break-before {
                page-break-before: always;
            }
            body {
                background-color: white !important;
                color: black !important;
            }
            .shadow-lg, .shadow-md, .shadow-sm {
                box-shadow: none !important;
            }
            table {
                font-size: 11px !important;
                border-collapse: collapse !important;
            }
            th, td {
                border: 1px solid black !important;
                padding: 4px 6px !important;
            }
        }
    </style>

    <div class="flex flex-col w-full gap-6">
        <!-- TOP HEADER -->
        <div class="bg-slate-900 border border-slate-800 text-white shadow-md no-print rounded-xl p-5 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center space-x-3">
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-white">RKT Mega-Prompt & Draft Generator</h1>
                    <p class="text-xs text-slate-400 font-medium">Asisten Perencanaan Berbasis Data (PBD) & Sistem Penjaminan Mutu Internal</p>
                </div>
            </div>
            <div class="flex items-center space-x-2 text-xs bg-black/40 px-3 py-1.5 rounded-full border border-slate-700">
                <span class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-slate-300">Standar Regulasi Kemendikdasmen 2026</span>
            </div>
        </div>

        <!-- MAIN LAYOUT (1 KOLOM) -->
        <div class="grid grid-cols-1 gap-6 w-full">
            <!-- BAGIAN ATAS: INPUT FORM & ACTION BOX -->
            <div class="space-y-6 no-print w-full">
                <form wire:submit.prevent class="space-y-6 w-full">
                    {{ $this->form }}
                </form>

                <!-- MEGA PROMPT ACTION BOX -->
                <x-filament::section icon="heroicon-o-command-line" header-color="teal">
                    <x-slot name="heading">
                        <span class="text-white">PROMPT GENERATOR ENGINE</span>
                    </x-slot>
                    <x-slot name="headerEnd">
                        <x-filament::badge color="success" size="xs">AI READY</x-filament::badge>
                    </x-slot>

                    <p class="text-xs text-slate-300 mb-4 leading-relaxed">
                        Salin mega-prompt sistematis ini untuk mengeksekusi penyusunan dokumen analitis 40+ halaman di LLM favorit Anda.
                    </p>

                    <div x-data="{
                        copyPrompt() {
                            const text = @js($this->megaPrompt);
                            navigator.clipboard.writeText(text).then(() => {
                                $tooltip('✓ Berhasil disalin ke clipboard!', { timeout: 3000 });
                            });
                        }
                    }">
                        <x-filament::button 
                            x-on:click="copyPrompt()" 
                            color="success" 
                            icon="heroicon-m-clipboard-document-check" 
                            class="w-full">
                            Copy Mega-Prompt (RKT Lengkap)
                        </x-filament::button>
                    </div>

                    <div class="mt-4 pt-4 border-t border-slate-800">
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold mb-3 text-center">Buka AI Favorit Anda</p>
                        <div class="grid grid-cols-3 gap-2">
                            <a href="https://gemini.google.com/" target="_blank" class="flex flex-col items-center justify-center p-2 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700">
                                <x-heroicon-o-sparkles class="w-4 h-4 text-blue-300 mb-1" />
                                <span class="text-[9px] text-slate-200">Gemini</span>
                            </a>
                            <a href="https://chatgpt.com/" target="_blank" class="flex flex-col items-center justify-center p-2 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700">
                                <x-heroicon-o-chat-bubble-left-right class="w-4 h-4 text-green-300 mb-1" />
                                <span class="text-[9px] text-slate-200">ChatGPT</span>
                            </a>
                            <a href="https://claude.ai/" target="_blank" class="flex flex-col items-center justify-center p-2 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700">
                                <x-heroicon-o-cpu-chip class="w-4 h-4 text-orange-300 mb-1" />
                                <span class="text-[9px] text-slate-200">Claude</span>
                            </a>
                        </div>
                    </div>
                </x-filament::section>
            </div>

            <!-- BAGIAN BAWAH: PREVIEW & DRAFT EDITOR SECTION -->
            <div class="flex flex-col space-y-4 w-full">
                <!-- TAB CONTROLS -->
                <div class="flex border-b border-slate-200 bg-white p-2 rounded-xl shadow-sm justify-between items-center no-print">
                    <div class="flex space-x-1">
                        <x-filament::button 
                            wire:click="toggleView(false)" 
                            :color="!$showPromptPreview ? 'primary' : 'gray'" 
                            size="sm">
                            Draf Dokumen RKT
                        </x-filament::button>
                        <x-filament::button 
                            wire:click="toggleView(true)" 
                            :color="$showPromptPreview ? 'primary' : 'gray'" 
                            size="sm">
                            Lihat Raw Mega-Prompt
                        </x-filament::button>
                    </div>
                    <x-filament::button 
                        onclick="window.print()" 
                        color="gray" 
                        icon="heroicon-m-printer" 
                        size="sm">
                        Cetak PDF
                    </x-filament::button>
                </div>

                <!-- PROMPT PREVIEW BOX -->
                @if($showPromptPreview)
                    <div class="bg-slate-900 text-emerald-400 p-6 rounded-xl font-mono text-xs overflow-x-auto max-h-[80vh] border border-slate-800 shadow-inner no-print">
                        <div class="flex justify-between items-center pb-4 mb-4 border-b border-slate-800">
                            <span class="text-slate-400">Isi Raw Mega-Prompt RKT</span>
                            <span class="text-slate-500">Format: Markdown</span>
                        </div>
                        <pre class="whitespace-pre-wrap leading-relaxed text-slate-300">{{ $this->megaPrompt }}</pre>
                    </div>
                @else
                    <!-- DOCUMENT PREVIEW CONTAINER -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 sm:p-12 md:p-16 space-y-8">
                        @php $formData = $this->data; @endphp

                        <!-- COVER DOKUMEN -->
                        <div class="text-center space-y-4 py-8 border-b-2 border-slate-900">
                            <h2 class="text-2xl font-extrabold tracking-wide uppercase text-slate-950">RENCANA KERJA TAHUNAN (RKT)</h2>
                            <h3 class="text-xl font-bold tracking-normal uppercase text-teal-900">{{ $formData['school_name'] }}</h3>
                            <p class="text-sm text-slate-500">NPSN: {{ $formData['npsn'] }} | Status: {{ $formData['status'] }} | TP: {{ $formData['tp'] }}</p>

                            <div class="h-32 flex items-center justify-center py-4">
                                @if(!empty($formData['logo_path']))
                                    <img src="{{ Storage::url($formData['logo_path']) }}" class="h-32 max-w-[150px] object-contain" alt="Logo Sekolah">
                                @else
                                    <div class="w-24 h-24 border-4 border-teal-800 rounded-full flex items-center justify-center bg-teal-50">
                                        <x-heroicon-o-academic-cap class="w-12 h-12 text-teal-800" />
                                    </div>
                                @endif
                            </div>

                            <p class="text-xs text-slate-400">Terintegrasi Program Prioritas & Perencanaan Berbasis Data (PBD)</p>
                            <p class="font-bold text-sm text-slate-800 uppercase mt-8">KEMENTERIAN PENDIDIKAN DASAR DAN MENENGAH<br>REPUBLIK INDONESIA</p>
                        </div>

                        <!-- LEMBAR PENGESAHAN -->
                        <div class="py-12 border-b border-slate-200 print-break-before">
                            <h3 class="text-lg font-bold text-slate-900 uppercase text-center mb-6">LEMBAR PENGESAHAN</h3>
                            <p class="text-xs text-slate-600 mb-8 leading-relaxed">
                                Setelah memperhatikan pertimbangan dari segenap pemangku kepentingan, dengan ini Rencana Kerja Tahunan (RKT) {{ $formData['school_name'] }} Tahun Pelajaran {{ $formData['tp'] }} disahkan untuk dapat diberlakukan dan dijadikan acuan seluruh program operasional satuan pendidikan sepanjang tahun berjalan.
                            </p>
                            <div class="grid grid-cols-3 gap-4 text-xs text-center font-semibold mt-16">
                                <div>
                                    <p>Menyetujui,</p>
                                    <p class="mb-16">Ketua Komite Sekolah</p>
                                    <p class="border-b border-slate-400 mx-4 pb-1 font-bold text-slate-900">{{ $formData['komite'] }}</p>
                                </div>
                                <div>
                                    <p>Mengesahkan,</p>
                                    <p class="mb-16">Pengawas Sekolah Pembina</p>
                                    <p class="border-b border-slate-400 mx-4 pb-1 font-bold text-slate-900">{{ $formData['pengawas'] }}</p>
                                    <p class="text-[10px] text-slate-500">NIP. {{ $formData['nip_pengawas'] }}</p>
                                </div>
                                <div>
                                    <p>Mengetahui,</p>
                                    <p class="mb-16">Kepala {{ $formData['school_name'] }}</p>
                                    <p class="border-b border-slate-400 mx-4 pb-1 font-bold text-slate-900">{{ $formData['headmaster_name'] }}</p>
                                    <p class="text-[10px] text-slate-500">NIP. {{ $formData['nip_kepsek'] }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- KATA PENGANTAR -->
                        <div class="py-6 space-y-3 print-break-before">
                            <h3 class="text-base font-bold text-slate-900 border-b pb-1">KATA PENGANTAR</h3>
                            <p class="text-xs leading-relaxed text-slate-700">Puji syukur senantiasa kami panjatkan ke hadirat Tuhan Yang Maha Esa atas tersusunnya Rencana Kerja Tahunan (RKT) {{ $formData['school_name'] }} Tahun Pelajaran {{ $formData['tp'] }}. Dokumen ini disusun sebagai wujud akuntabilitas internal dan eksternal satuan pendidikan dalam merancang perbaikan mutu secara berkelanjutan.</p>
                            <p class="text-xs text-right pt-4">Juli, 2026<br><strong>Kepala Sekolah</strong></p>
                        </div>

                        <!-- DETAIL 17 PROGRAM -->
                        <div class="py-6 space-y-4 print-break-before">
                            <h3 class="text-base font-bold text-slate-900 border-b pb-1">E. DETAIL 17 PROGRAM OPERASIONAL TAHUNAN</h3>

                            @foreach($this->programs as $p)
                                <div class="my-6 overflow-x-auto print-break-before">
                                    <h4 class="text-sm font-bold text-teal-800 bg-teal-50 px-3 py-2 rounded border border-teal-200 mb-2">{{ $p['prog'] }}</h4>
                                    <table class="min-w-full text-xs border border-slate-300 rounded-lg overflow-hidden">
                                        <thead>
                                            <tr class="bg-slate-100 border-b border-slate-300 text-slate-700 text-left">
                                                <th class="p-2 border-r border-slate-300 w-1/4">Elemen / Program</th>
                                                <th class="p-2 border-r border-slate-300 w-1/2">Detail Pelaksanaan Operasional</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200">
                                            <tr><td class="p-2 font-medium bg-slate-50 border-r border-slate-300">Kegiatan Utama</td><td class="p-2">{{ $p['keg'] }}</td></tr>
                                            <tr><td class="p-2 font-medium bg-slate-50 border-r border-slate-300">Tujuan Kegiatan</td><td class="p-2">{{ $p['tuj'] }}</td></tr>
                                            <tr><td class="p-2 font-medium bg-slate-50 border-r border-slate-300">Sasaran / Peserta</td><td class="p-2">{{ $p['sas'] }}</td></tr>
                                            <tr><td class="p-2 font-medium bg-slate-50 border-r border-slate-300">Indikator Keberhasilan</td><td class="p-2">{{ $p['ind'] }}</td></tr>
                                            <tr><td class="p-2 font-medium bg-slate-50 border-r border-slate-300">Target Capaian (SMART)</td><td class="p-2">{{ $p['tar'] }}</td></tr>
                                            <tr><td class="p-2 font-medium bg-slate-50 border-r border-slate-300">Penanggung Jawab</td><td class="p-2"><strong>{{ $p['pj'] }}</strong></td></tr>
                                            <tr><td class="p-2 font-medium bg-slate-50 border-r border-slate-300">Waktu Pelaksanaan</td><td class="p-2">{{ $p['wkt'] }}</td></tr>
                                            <tr><td class="p-2 font-medium bg-slate-50 border-r border-slate-300">Sumber Dana Estimasi</td><td class="p-2">{{ $p['dana'] }}</td></tr>
                                            <tr><td class="p-2 font-medium bg-slate-50 border-r border-slate-300">Bukti Fisik / Outbond Dokumen</td><td class="p-2">{{ $p['bukti'] }}</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>