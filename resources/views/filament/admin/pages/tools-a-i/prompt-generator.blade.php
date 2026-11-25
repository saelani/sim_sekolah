<x-filament-panels::page class="!max-w-none w-full">
@vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Layout 1 Kolom Penuh --}}
    <div class="grid grid-cols-1 gap-6 w-full" x-data="promptGenerator()">
        
        {{-- Bagian Atas: Navigasi Mode & Form Konfigurasi --}}
        <div class="space-y-6 w-full">
            
            {{-- Tombol Navigasi Mode --}}
            <div class="flex flex-wrap gap-2 p-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <x-filament::button 
                    wire:click="setMode('presentasi')" 
                    :color="$mode === 'presentasi' ? 'primary' : 'gray'"
                    size="sm"
                    icon="heroicon-o-presentation-chart-bar">
                    Slide Presentasi
                </x-filament::button>

                <x-filament::button 
                    wire:click="setMode('soal')" 
                    :color="$mode === 'soal' ? 'primary' : 'gray'"
                    size="sm"
                    icon="heroicon-o-academic-cap">
                    Generator Soal
                </x-filament::button>

                <x-filament::button 
                    wire:click="setMode('bahan')" 
                    :color="$mode === 'bahan' ? 'primary' : 'gray'"
                    size="sm"
                    icon="heroicon-o-book-open">
                    Bahan Ajar Siswa
                </x-filament::button>

                <x-filament::button 
                    wire:click="setMode('modul')" 
                    :color="$mode === 'modul' ? 'primary' : 'gray'"
                    size="sm"
                    icon="heroicon-o-document-text">
                    RPP Mendalam
                </x-filament::button>

                <x-filament::button 
                    wire:click="setMode('rubrik')" 
                    :color="$mode === 'rubrik' ? 'primary' : 'gray'"
                    size="sm"
                    icon="heroicon-o-table-cells">
                    Rubrik & LKPD
                </x-filament::button>
            </div>

            {{-- Form Schema Filament --}}
            <form wire:submit.prevent="generatePrompt" class="w-full">
                {{ $this->form }}
            </form>
        </div>

        {{-- Bagian Bawah: Hasil Prompt & Tombol Aksi --}}
        <div class="w-full">
            <x-filament::section class="w-full !max-w-none">
                <div class="space-y-4 w-full">
                    <div class="flex items-center justify-between w-full">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Hasil Prompt AI</span>
                        <span class="text-xs px-2 py-0.5 bg-primary-50 text-primary-600 rounded-full font-semibold uppercase">
                            {{ $mode }}
                        </span>
                    </div>

                    {{-- Textarea Hasil dengan w-full mutlak --}}
                    <div class="w-full">
                        <textarea 
                            x-ref="promptTextarea"
                            wire:model.live="generatedPrompt"
                            readonly 
                            rows="14" 
                            class="w-full text-xs font-mono p-3 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 rounded-lg border-gray-300 dark:border-gray-700 focus:ring-0 leading-relaxed resize-y"
                            placeholder="Prompt akan tergenerasi otomatis di sini..."
                        ></textarea>
                    </div>

                    {{-- Tombol Salin --}}
                    <x-filament::button 
                        icon="heroicon-o-clipboard-document"
                        x-on:click="copyPrompt()"
                        class="w-full">
                        <span x-text="buttonText"></span>
                    </x-filament::button>

                    {{-- Tautan Eksternal AI --}}
                    <div class="grid grid-cols-2 gap-2 pt-2 w-full">
                        <a href="https://chat.openai.com" target="_blank" class="inline-flex items-center justify-center gap-1 px-3 py-2 text-xs font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors shadow-sm">
                            ChatGPT
                        </a>
                        <a href="https://gemini.google.com" target="_blank" class="inline-flex items-center justify-center gap-1 px-3 py-2 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm">
                            Gemini
                        </a>
                    </div>
                </div>
            </x-filament::section>
        </div>

    </div>

    {{-- Skrip AlpineJS untuk Sinkronisasi dan Clipboard --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('promptGenerator', () => ({
                buttonText: 'Salin Seluruh Prompt',
                init() {
                    this.$watch('$wire.generatedPrompt', (value) => {
                        if (this.$refs.promptTextarea) {
                            this.$refs.promptTextarea.value = value;
                        }
                    });
                },
                copyPrompt() {
                    const el = this.$refs.promptTextarea;
                    if (!el || !el.value) return;

                    const textToCopy = el.value;

                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(textToCopy).then(() => {
                            this.showSuccess();
                        }).catch(() => {
                            this.fallbackCopy(el);
                        });
                    } else {
                        this.fallbackCopy(el);
                    }
                },
                fallbackCopy(el) {
                    const temp = document.createElement('textarea');
                    temp.value = el.value;
                    temp.style.position = 'fixed';
                    temp.style.left = '-9999px';
                    temp.style.top = '0';
                    document.body.appendChild(temp);
                    
                    temp.focus();
                    temp.select();
                    temp.setSelectionRange(0, temp.value.length);
                    
                    try {
                        document.execCommand('copy');
                        this.showSuccess();
                    } catch (err) {
                        alert('Gagal menyalin otomatis. Silakan blok teks secara manual dan tekan Ctrl+C.');
                    } finally {
                        document.body.removeChild(temp);
                    }
                },
                showSuccess() {
                    this.buttonText = '✅ Berhasil Disalin Semua!';
                    setTimeout(() => {
                        this.buttonText = 'Salin Seluruh Prompt';
                    }, 2500);
                }
            }));
        });
    </script>
</x-filament-panels::page>