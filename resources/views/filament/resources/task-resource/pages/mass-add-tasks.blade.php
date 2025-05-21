<x-filament-panels::page>
    <x-filament::card>
        {{ $this->form }}

        <br>
        <x-filament::button wire:click="create">
            Создать
        </x-filament::button>

    </x-filament::card>
</x-filament-panels::page>
