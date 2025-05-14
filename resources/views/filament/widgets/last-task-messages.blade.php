<x-filament::widget>
    <x-filament::card>
        <div class="text-xl font-bold mb-4">Ошибки</div>

        <div>
            <table class="table-auto">
            <thead>
                <tr class="bg-gray-100">
                <th class="px-4 py-2 text-left">Дата</th>
                <th class="px-4 py-2 text-left">Текст</th>
                <th class="px-4 py-2 text-left">Название задания</th>
                </tr>
            </thead>
            <tbody>

                 @foreach ($messages as $message)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $message->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2">{{ $message->text }}</td>
                        <td class="px-4 py-2">{{ $message->task->name }}</td>
                    </tr>
                @endforeach

            </tbody>
            </table>
        </div>

    </x-filament::card>
</x-filament::widget>
