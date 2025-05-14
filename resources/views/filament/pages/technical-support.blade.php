<x-filament-panels::page>
    <x-filament::card>

        <div class="mb-4">
            <x-filament-forms::field-wrapper.label>
                E-mail
            </x-filament-forms::field-wrapper.label>
            <x-filament::input.wrapper>
                <x-filament::input
                    type="email"
                    wire:model="email"
                />
            </x-filament::input.wrapper>
        </div>

        <div class="mb-4">
            <x-filament-forms::field-wrapper.label>
                Имя
            </x-filament-forms::field-wrapper.label>
            <x-filament::input.wrapper>
                <x-filament::input
                    type="text"
                    wire:model="name"
                />
            </x-filament::input.wrapper>
        </div>

        <div class="mb-4">
            <x-filament-forms::field-wrapper.label>
                Тема обращения
            </x-filament-forms::field-wrapper.label>
            <x-filament::input.wrapper>
                <x-filament::input
                    type="text"
                    wire:model="subject"
                />
            </x-filament::input.wrapper>
        </div>

        <div class="mb-4">
            <x-filament-forms::field-wrapper.label>
                Сообщение
            </x-filament-forms::field-wrapper.label>
            <x-filament::input.wrapper>
                <textarea class="block h-full w-full border-none bg-transparent px-3 py-1.5 text-base text-gray-950 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6"
                    wire:model="message"
                    data-has-alpine-state="true"></textarea>
            </x-filament::input.wrapper>
        </div>

        <x-filament::button wire:click="send" class="mt-2">
            Отправить
        </x-filament::button>


    </x-filament::card>
</x-filament-panels::page>
