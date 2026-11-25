<x-filament-panels::page>
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <div class="grid grid-cols-1 gap-6">
        
        <!-- Bagian Atas: Form Input Filament -->
        <div>
            {{ $this->form }}
        </div>

        <!-- Bagian Bawah: Output Prompt -->
        <div class="space-y-4">
            <x-filament::section icon="heroicon-o-sparkles" header-heading="Prompt Dihasilkan">
                <x-slot name="headerEnd">
                    <x-filament::button
                        icon="heroicon-o-document-duplicate"
                        color="primary"
                        x-data="{}"
                        x-on:click="
                            window.navigator.clipboard.writeText($wire.promptOutput);
                            $tooltip('Berhasil disalin!', { timeout: 2000 });
                        "
                    >
                        Salin Prompt
                    </x-filament::button>
                </x-slot>

                <p class="text-xs text-gray-500 mb-3">
                    Prompt di bawah akan diperbarui otomatis setiap kali Anda mengubah isi form di atas.
                </p>

                <x-filament::input.wrapper>
                    <textarea
                        wire:model="promptOutput"
                        rows="18"
                        readonly
                        class="fi-input block w-full border-none bg-transparent p-3 font-mono text-xs leading-relaxed text-gray-950 focus:ring-0 dark:text-white"
                    ></textarea>
                </x-filament::input.wrapper>

                <!-- Tombol Pintasan ke AI -->
                <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700 flex flex-wrap gap-2">
                    <span class="text-xs font-medium text-gray-500 w-full mb-1">Buka Platform AI:</span>
                    <x-filament::button tag="a" href="https://chatgpt.com/" target="_blank" color="success" size="xs" icon="heroicon-o-arrow-top-right-on-square">
                        ChatGPT
                    </x-filament::button>
                    <x-filament::button tag="a" href="https://gemini.google.com/" target="_blank" color="info" size="xs" icon="heroicon-o-arrow-top-right-on-square">
                        Gemini
                    </x-filament::button>
                    <x-filament::button tag="a" href="https://claude.ai/" target="_blank" color="warning" size="xs" icon="heroicon-o-arrow-top-right-on-square">
                        Claude
                    </x-filament::button>
                </div>
            </x-filament::section>
        </div>

    </div>
</x-filament-panels::page>