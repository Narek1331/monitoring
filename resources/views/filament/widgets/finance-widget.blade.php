<x-filament::widget>
    <x-filament::card>
        <div class="text-xl font-bold mb-4">Финансы</div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <div class="text-sm text-gray-600">Текущий баланс</div>
                <div class="text-2xl font-semibold text-green-600">
                    {{ number_format($balance, 2, '.', ' ') }} ₽
                </div>
            </div>
            <div>
                <div class="text-sm text-gray-600">Расход в сутки</div>
                <div class="text-2xl font-semibold text-red-600">
                    {{ number_format($dailyExpense, 2, '.', ' ') }} ₽
                </div>
            </div>
            <div>
                <div class="text-sm text-gray-600">Примерная дата продления</div>
                <div class="text-2xl font-semibold">
                    {{ $renewalDate }}
                </div>
            </div>
        </div>

        {{-- Вкладки --}}
        <div x-data="{ tab: 'topup' }" class="mt-6">
            <div class="flex space-x-4 border-b mb-4">
                <button
                    class="pb-2 border-b-2"
                    style="margin-right: 10px!important"
                    :class="tab === 'topup' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500'"
                    @click="tab = 'topup'"
                >
                    Пополнить баланс
                </button>
                <button
                    class="pb-2 border-b-2"
                    style="margin-right: 10px!important"
                    :class="tab === 'transactions' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500'"
                    @click="tab = 'transactions'"
                >
                    Движения по счету
                </button>
                <button
                    class="pb-2 border-b-2"
                    style="margin-right: 10px!important"
                    :class="tab === 'docs' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500'"
                    @click="tab = 'docs'"
                >
                    Счета и акты
                </button>
            </div>

            <div x-show="tab === 'topup'">
                <p class="text-gray-600">
                    Пополнить баланс
                </p>
            </div>
            <div x-show="tab === 'transactions'" x-cloak>
                <p class="text-gray-600">
                    Движения по счету
                </p>
            </div>
            <div x-show="tab === 'docs'" x-cloak>
                <p class="text-gray-600">
                    Счета и акты
                </p>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>
