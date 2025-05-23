<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VerificationMethod;
class VerificationMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
               'title' => 'Проверка доступности сайта с поиском слова на странице (метод GET)',
               'short_title' => 'метод GET',
               'description'  => 'Этот метод нужен для того, чтобы проверить содержание вашего сайта. Мы будем не только проверять работает ли ваш сайт, но и искать на вашем сайте указанную вами фразу или слово. Выбирая этот метод, не забудьте указать слово, которое нужно искать на вашем сайте. Размер страницы не должен превышать 200 Кб.',
               'order_by'  => 1,
            ],
            [
               'title' => 'Проверка сайта на вирусы и наличие в разных базах',
               'short_title' => 'вирусы',
               'description'  => 'Мы проверяем отсутствие на вашем сайте вирусных вставок, редиректов (мобильных, поисковых и др.), проверяем ваш сайт по базам Роскомнадзора, антивирусов, черным спискам Яндекса и Google. В случае любых отклонений от нормы уведомляем вас.',
               'order_by'  => 2,
            ],
            [
               'title' => 'Контроль изменений файлов на сервере',
               'short_title' => 'контроль файлов',
               'description'  => 'Проверка файлов вашего сайта или сервера на изменение, добавление, удаление без вашего ведома. В случае изменения любого файла даже на байт вы получите уведомление.',
               'order_by'  => 3,
            ],
            [
               'title' => 'Мониторинг наличия ссылок и HTML кода',
               'short_title' => 'мониторинг ссылок',
               'description'  => 'Для SEO специалистов важно контролировать наличие ссылок на продвигаемый ресурс на сторонних сайтах. Ведь ссылку могут разместить, а потом убрать. Каждый день заходить и проверять наличие ссылок очень затруднительно.',
               'order_by'  => 4,
            ],
            [
               'title' => 'Простая проверка доступности сайта или сервера (метод HEAD)',
               'short_title' => 'простая проверкак',
               'description'  => 'Выберите данный метод проверки, если вам просто необходимо проверять доступен ваш сайт или нет. Мы отправляем запрос к вашему сайту и получаем от него заголовки ответа. На основании них мы можем судить - работает ваш сайт или нет. В 99% случаев вам необходим именно этот метод.',
               'order_by'  => 5,
            ],
            [
               'title' => 'Проверка доступности сайта с отправкой данных формы (метод POST)',
               'short_title' => 'метод POST',
               'description'  => 'Этим методом вы можете проверять, например, работу формы поиска или формы авторизации на сайте. Мы будем отправлять на вашу форму указанные вами данные и смотреть за результатом - искать в ответе то, что вы укажете.',
               'order_by'  => 6,
            ],
            [
               'title' => 'Проверка внутренних ресурсов сервера (место на диске, загрузка, uptime и др)',
               'short_title' => 'Проверка внутренних ресурсов',
               'description'  => 'Выбирайте данный метод, если вы хотите контролировать ресурсы своего сервера и в случае превышения допустимого значения получать уведомления.',
               'order_by'  => 7,
            ],
            [
               'title' => 'Проверка доступности FTP сервера',
               'short_title' => 'FTP сервер',
               'description'  => 'Данный метод служит для проверки работы (доступности) FTP сервера.',
               'order_by'  => 8,
            ],
        ];

        foreach($datas as $data)
        {
            VerificationMethod::create($data);
        }

    }
}
