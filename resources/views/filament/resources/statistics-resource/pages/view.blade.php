<x-filament-panels::page>
    <x-filament::card>

        <p><strong>URL:</strong> {{ $this->record->address_ip }}</p>
        <p><strong>SSL сертификат истекает:</strong> {{ $this->loadSslExpiration() }}</p>
        <p><strong>Есть вирус:</strong> {{ $this->isSiteClean() ? 'Да' : 'Нет' }}</p>
        <p><strong>Скорость загрузки сайта:</strong> {{ $this->getLoadSpeedMs() }} мс</p>



        <div class="" style="margin-top: 10px!important">

        <div class="overflow-x-auto rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Ошибка</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Дата</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($this->getTaskMessages() as $message)
                        <tr>
                            <td class="px-6 py-4">{{ $message->text }}</td>
                            <td class="px-6 py-4">{{ $message->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    </x-filament::card>
</x-filament-panels::page>
