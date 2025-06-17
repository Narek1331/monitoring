<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Справка кодов</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <style>
    #sidebar.fixed {
      position: fixed;
      top: 60px;
      width: 263px;
    }
  </style>
</head>
<body>
  <div class="container mt-4">
    <div class="row">
      <!-- Sidebar -->
      <aside class="col-md-3 d-none d-md-block" id="sidebar">
        <div class="fixed">
          <ul class="list-group mb-4 small">
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <a href=""><i class="fa fa-circle-question me-2"></i>Справка (FAQ)</a>
              <span class="badge bg-warning text-dark"><i class="fa fa-thumbs-up"></i></span>
            </li>
            <li class="list-group-item">
              <a href=""><i class="fa fa-headset me-2"></i>Служба поддержки</a>
            </li>
            <li class="list-group-item">
              <a href=""><i class="fa fa-file me-2"></i>Лицензионный договор</a>
            </li>
            <li class="list-group-item">
              <a href=""><i class="fa fa-clipboard me-2"></i>Документы и реквизиты</a>
            </li>
            <li class="list-group-item">
              <a href=""><i class="fa fa-bullhorn me-2"></i>Новости</a>
            </li>
            <li class="list-group-item">
              <a href="/account/register" class="text-danger"><i class="fa fa-pen-to-square me-2"></i>Зарегистрировать аккаунт</a>
            </li>
            <li class="list-group-item">
              <a href="/account/login" class="text-primary"><i class="fa fa-user me-2"></i>Войти в аккаунт</a>
            </li>
          </ul>
        </div>
      </aside>

      <!-- Main Content -->
      <main class="col-md-9">
        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th scope="col">Код</th>
                <th scope="col">Описание</th>
              </tr>
            </thead>
            <tbody>
              <tr><td><strong>0</strong></td><td>000 Not Answer – проверяемый ресурс не ответил на наши запросы в течение установленного времени</td></tr>
              <tr><td><strong>1</strong></td><td>001 Exceeded Redirects – превышено число редиректов (больше 1 раза)</td></tr>
              <tr><td><strong>2</strong></td><td>002 Code Not Found – получили код ответа, отличный от ожидаемого</td></tr>
              <tr><td><strong>3</strong></td><td>003 Word Not Found – отсутствует проверяемое слово или фраза</td></tr>
              <tr><td><strong>4</strong></td><td>004 Exceeded Size – размер страницы больше 200 Кб</td></tr>
              <tr><td><strong>5</strong></td><td>005 Not Answer From FTP – невозможно соединиться с FTP</td></tr>
              <tr><td><strong>6</strong></td><td>006 Exceeded Size BD – размер страницы при проверке БД больше 10 Кб</td></tr>
              <tr><td><strong>7</strong></td><td>007 BD Not Work – база данных не работает, пришел ответ не OK</td></tr>
              <tr><td><strong>8</strong></td><td>008 Post Not Work – невозможно соединиться с почтовым сервером</td></tr>
              <tr><td><strong>9</strong></td><td>009 DNS Not Work – не найдены DNS-записи домена</td></tr>
              <tr><td><strong>10</strong></td><td>010 DNS Data Not Match – не совпадают DNS-записи с указанными</td></tr>
              <tr><td><strong>11</strong></td><td>011 DNS Data Not Match – DNS сервера не совпадают или недоступны</td></tr>
              <tr><td><strong>12</strong></td><td>012 DNS Data Not Match – ошибка определения DNS-серверов</td></tr>
              <tr><td><strong>13</strong></td><td>013 Ping Loss – потеря пакетов при пинге выше допустимого</td></tr>
              <tr><td><strong>14</strong></td><td>014 Ping Problem – адрес не найден, возможны проблемы с DNS</td></tr>
              <tr><td><strong>15</strong></td><td>015 Unrecognized Error – нераспознанная ошибка при проверке</td></tr>
              <tr><td><strong>17</strong></td><td>017 Unrecognized Error – нераспознанная ошибка при проверке</td></tr>
              <tr><td><strong>18</strong></td><td>018 Number Is Not In The Range – число вне указанного диапазона</td></tr>
              <tr><td><strong>19</strong></td><td>019 Answer Is Not Number – ответ не является числом</td></tr>
              <tr><td><strong>20</strong></td><td>020 Page Size Is Not In The Range – размер страницы вне диапазона</td></tr>
              <tr><td><strong>21</strong></td><td>021 File Control: Base Does Not Exist – база слепков файлов не существует</td></tr>
              <tr><td><strong>22</strong></td><td>022 File Control: Base Is Empty – база слепков файлов пустая</td></tr>
              <tr><td><strong>24</strong></td><td>024 File Control: Base Change – база слепков была изменена</td></tr>
              <tr><td><strong>25</strong></td><td>025 File Control: Changes Are Detected – обнаружены изменения в файлах</td></tr>
              <tr><td><strong>27</strong></td><td>027 File Control: Script Has Not Answered – контрольный скрипт не ответил</td></tr>
              <tr><td><strong>28</strong></td><td>028 The Searched Text Appeared – искомый текст появился на странице</td></tr>
              <tr><td><strong>31</strong></td><td>031 Telnet Not Answer – telnet‑соединение не удалось</td></tr>
              <tr><td><strong>32</strong></td><td>032 Telnet: Word Not Found – искомая фраза не найдена при telnet</td></tr>
              <tr><td><strong>41</strong></td><td>041 Content Change Detected – содержание страницы изменилось</td></tr>
              <tr><td><strong>51</strong></td><td>051 Virus And Security Control: Changes Are Detected – изменения выявлены при мониторинге вирусов</td></tr>
              <tr><td><strong>61</strong></td><td>061 SSL Connect Failed – ошибка HTTPS‑соединения</td></tr>
              <tr><td><strong>62</strong></td><td>062 Failure Receiving Data – соединение было сброшено</td></tr>
              <tr><td><strong>63</strong></td><td>063 Empty Reply From Server – пришел пустой ответ от сервера</td></tr>
              <tr><td><strong>81</strong></td><td>081 Domain Expired – срок регистрации домена подошел к концу</td></tr>
              <tr><td><strong>91</strong></td><td>091 SSL Expired – срок действия SSL‑сертификата подходит к концу</td></tr>
              <tr><td><strong>92</strong></td><td>092 SSL Invalid – SSL‑сертификат выдан для другого домена или недействителен</td></tr>
              <tr><td><strong>200</strong></td><td>200 ОК – сервер успешно ответил на запрос</td></tr>
              <tr><td><strong>204</strong></td><td>204 No Content – сервер ответил только заголовками</td></tr>
              <tr><td><strong>250</strong></td><td>250 Error – ответ отличный от 200 OK</td></tr>
              <tr><td><strong>301</strong></td><td>301 Moved Permanently – ресурс перемещен постоянно</td></tr>
              <tr><td><strong>302</strong></td><td>302 Moved Temporarily – ресурс перемещен временно</td></tr>
              <tr><td><strong>303</strong></td><td>303 See Other – документ нужно запросить по другому URI</td></tr>
              <tr><td><strong>307</strong></td><td>307 Temporary Redirect – временный редирект</td></tr>
              <tr><td><strong>308</strong></td><td>308 Redirect – постоянный редирект</td></tr>
              <tr><td><strong>400</strong></td><td>400 Bad Request – синтаксическая ошибка в запросе</td></tr>
              <tr><td><strong>401</strong></td><td>401 Unauthorized – требуется авторизация или неверные учетные данные</td></tr>
              <tr><td><strong>402</strong></td><td>402 Payment Required – требуется оплата для доступа</td></tr>
              <tr><td><strong>403</strong></td><td>403 Forbidden – доступ запрещен настройками сервера</td></tr>
              <tr><td><strong>404</strong></td><td>404 Not Found – ресурс не найден</td></tr>
              <tr><td><strong>405</strong></td><td>405 Method Not Allowed – метод не поддерживается</td></tr>
              <tr><td><strong>406</strong></td><td>406 Not Acceptable – сервер не может удовлетворить требования</td></tr>
              <tr><td><strong>408</strong></td><td>408 Request Timeout – время ожидания запроса истекло</td></tr>
              <tr><td><strong>409</strong></td><td>409 Conflict – конфликт запроса</td></tr>
              <tr><td><strong>410</strong></td><td>410 Gone – ресурс был удален</td></tr>
              <tr><td><strong>411</strong></td><td>411 Length Required – сервер ожидает данные, но они не переданы</td></tr>
              <tr><td><strong>418</strong></td><td>418 I’m a teapot – шуточный ответ</td></tr>
              <tr><td><strong>423</strong></td><td>423 Method locked due to malicious activity – IP‑адрес был заблокирован</td></tr>
              <tr><td><strong>429</strong></td><td>429 Too Many Requests – слишком много запросов за короткое время</td></tr>
              <tr><td><strong>440</strong></td><td>440 Login Timeout – тайм‑аут сессии входа</td></tr>
              <tr><td><strong>451</strong></td><td>451 Unavailable For Legal Reasons – недоступно по юридическим причинам</td></tr>
              <tr><td><strong>500</strong></td><td>500 Internal Server Error – внутренняя ошибка сервера</td></tr>
              <tr><td><strong>501</strong></td><td>501 Not Implemented – сервер не поддерживает запрошенный функционал</td></tr>
              <tr><td><strong>502</strong></td><td>502 Bad Gateway – промежуточный сервер выдал ошибку</td></tr>
              <tr><td><strong>503</strong></td><td>503 Service Unavailable – сервис недоступен</td></tr>
              <tr><td><strong>504</strong></td><td>504 Gateway Timeout – тайм‑аут шлюза</td></tr>
              <tr><td><strong>505</strong></td><td>505 HTTP Version Not Supported – версия HTTP не поддерживается</td></tr>
              <tr><td><strong>507</strong></td><td>507 Insufficient Storage – недостаточно места на сервере</td></tr>
              <tr><td><strong>508</strong></td><td>508 Loop Detected – обнаружен бесконечный редирект</td></tr>
              <tr><td><strong>509</strong></td><td>509 Bandwidth Limit Exceeded – превышен трафик</td></tr>
              <tr><td><strong>510</strong></td><td>510 Not Extended – отсутствует требуемое расширение</td></tr>
              <tr><td><strong>511</strong></td><td>511 Network Authentication Required – требуется сетевая аутентификация</td></tr>
              <tr><td><strong>520</strong></td><td>520 Unknown Error (Cloudflare) – неизвестная ошибка от Cloudflare</td></tr>
              <tr><td><strong>521</strong></td><td>521 Web Server Is Down (Cloudflare) – веб‑сервер недоступен</td></tr>
              <tr><td><strong>522</strong></td><td>522 Connection Timed Out (Cloudflare) – соединение не отвечает</td></tr>
              <tr><td><strong>523</strong></td><td>523 Origin Is Unreachable (Cloudflare) – источник недоступен</td></tr>
              <tr><td><strong>525</strong></td><td>525 SSL Handshake Failed (Cloudflare) – не удалось выполнить SSL рукопожатие</td></tr>
              <tr><td><strong>526</strong></td><td>526 Invalid SSL Certificate (Cloudflare) – недействительный SSL‑сертификат</td></tr>
              <tr><td><strong>530</strong></td><td>530 Origin DNS Error (Cloudflare) – Cloudflare не может разрешить DNS</td></tr>
              <tr><td><strong>555</strong></td><td>555 Unknown – неизвестная ошибка или запрос заблокирован (Group IB)</td></tr>
            </tbody>
          </table>
        </div>
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
