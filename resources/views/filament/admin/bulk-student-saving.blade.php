<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6 flex items-center gap-3">
            <x-filament::button type="submit">
                Simpan Transaksi Tabungan
            </x-filament::button>

            <x-filament::button color="gray" tag="a" :href="\App\Filament\Admin\Resources\StudentSavings\StudentSavingResource::getUrl('index')">
                Batal
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>