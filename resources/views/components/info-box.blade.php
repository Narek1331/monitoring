<div class="card bg-white dark:bg-gray-800 rounded-lg shadow-md">
    <div class="card-body p-6 text-gray-900 dark:text-gray-100">
        <p class="mb-4">
            1. <a style="color: blue !important; text-decoration: underline;" href="{{ route('download.code') }}" download="code.php" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-600">Загрузите этот файл</a>
        </p>
        <p class="mb-4">2. Скопируйте этот токен</p>
        <p class="font-mono text-lg bg-gray-100 dark:bg-gray-700 p-2 rounded">{{
            $this->data['token'] }}</p>

        <p class="mb-4">3. Откройте загруженный файл code.php</p>
        <p class="mb-4">4. Вставьте скопированный токен в переменную TOKEN</p>
        <p class="mb-4">5. Добавьте файл code.php в основной каталог вашего проекта (например /public_html/code.php)</p>
        <p class="mb-4">6. Запустите файл code.php с помощью команды php code.php (при необходимости можно использовать Supervisor для запуска в фоновом режиме)</p>

    </div>
</div>
