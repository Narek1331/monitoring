@php
    $user = filament()->auth()->user();
    $items = filament()->getUserMenuItems();

    $profileItem = $items['profile'] ?? $items['account'] ?? null;
    $profileItemUrl = $profileItem?->getUrl();
    $profilePage = filament()->getProfilePage();
    $hasProfileItem = filament()->hasProfile() || filled($profileItemUrl);

    $logoutItem = $items['logout'] ?? null;

    $items = \Illuminate\Support\Arr::except($items, ['account', 'logout', 'profile']);
@endphp

<x-filament::dropdown>
    <x-slot name="trigger">
        <div class="flex items-center space-x-2">
            <x-filament-panels::avatar.user :user="$user" style="margin-right: 10px!important"/>

            <p class="hidden md:block text-sm font-medium text-gray-900">
                {{ auth()->user()->name }}
            </p>
        </div>
    </x-slot>


    <x-filament::dropdown.list>
        <x-filament::dropdown.list.item>
            <a href="/account/profile">
                Профиль
            </a>
        </x-filament::dropdown.list.item>

        <x-filament::dropdown.list.item >
            Настройка конфиденциальности
        </x-filament::dropdown.list.item>

        <x-filament::dropdown.list.item
            :action="$logoutItem?->getUrl() ?? filament()->getLogoutUrl()"
            :color="$logoutItem?->getColor()"
            :icon="$logoutItem?->getIcon() ?? \Filament\Support\Facades\FilamentIcon::resolve('panels::user-menu.logout-button') ?? 'heroicon-m-arrow-left-on-rectangle'"
            method="post"
            tag="form"
        >
            {{ $logoutItem?->getLabel() ?? __('filament-panels::layout.actions.logout.label') }}
        </x-filament::dropdown.list.item>
    </x-filament::dropdown.list>
</x-filament::dropdown>
