<?php

namespace App\Services;

use Carbon\Carbon;

class CheckService
{
    protected $taskService;
    protected $httpService;
    protected $whoisService;
    protected $siteScannerService;
    protected $virusTotalService;

    public function __construct(
        TaskService $taskService,
        HttpService $httpService,
        WhoisService $whoisService,
        SiteScannerService $siteScannerService,
        VirusTotalService $virusTotalService
        )
    {
        $this->taskService = $taskService;
        $this->httpService = $httpService;
        $this->whoisService = $whoisService;
        $this->siteScannerService = $siteScannerService;
        $this->virusTotalService = $virusTotalService;
    }
    public function index()
    {
        $tasks = $this->taskService->getAllTasks();

        foreach($tasks as $task)
        {
            $this->check($task);
        }
    }

    public function check($task)
    {
        if($task && $task->status)
        {
            if($task->verificationMethod->slug == 'proverka_dostupnosti_saita_s_poiskom_slova_na_stranice_metod_get')
            {
                $this->proverkaDostupnostiSaitaSPoiskomSlovaNaStraniceMetodGet($task);
            }else if($task->verificationMethod->slug == 'proverka_saita_na_virusy_i_nalicie_v_raznyx_bazax')
            {
                $this->proverkaSaitaNaVirusyINalicieVRaznyxBazax($task);
            }else if($task->verificationMethod->slug == 'kontrol_izmenenii_failov_na_servere')
            {
                $this->kontrolIzmeneniiFailovNaServere($task);
            }else if($task->verificationMethod->slug == 'monitoring_naliciia_ssylok_i_html_koda')
            {
                $this->monitoringNaliciiaSsylokIHtmlKoda($task);
            }
        }
    }

    private function proverkaDostupnostiSaitaSPoiskomSlovaNaStraniceMetodGet($task)
    {
        if(!$task->last_check_date)
        {
            $task->last_check_date = now();
            $task->save();
        }

        $givenTime = Carbon::parse($task['last_check_date']);
        $currentTime = Carbon::now();

        if ($givenTime->diffInMinutes($currentTime) >= $task->frequency_of_inspection) {
            $task->last_check_date = now();
            $task->save();
        }else{
            return;
        }

        $url = $task['protocol'] . $task['address_ip'];
        $errorMessage = $task->error_message;
        $notifyOnRecovery = $task->notify_on_recovery;
        $taskName = $task->name;

        if($port = $task['port'])
        {
            $url .= ":$port";
        }

        $httpData = $this->httpService->makeSimpleRequest($url,$task);

        $httpStatusCode = $httpData['status'] ?? 500;
        $lastMessage = $task->messages->last();

        if(!$httpData)
        {
            $textMessage = $errorMessage ?? "$taskName : Ошибка сервера";
            if($lastMessage->text != $textMessage)
            {
                $task->messages()->create([
                    'status' => false,
                    'text' => $textMessage,
                    'status_code' => 500
                ]);
            }

            return;
        }

        if($notifyOnRecovery)
        {
            if(!$lastMessage->status)
            {
                $task->messages()->create([
                    'status' => true,
                    'text' => "$taskName : Сервер полностью восстановлен",
                    'status_code' => $httpStatusCode
                ]);
            }
        }


        $searchTextInResponse = $task->search_text_in_response;
        $textPresenceErrorCheck = $task->text_presence_error_check;
        $validResponseCode = $task->valid_response_code;
        $ignoredErrorCodes = $task->ignored_error_codes;
        $alertOnSpecificCodes = $task->alert_on_specific_codes;

        if ($searchTextInResponse && strpos($httpData['html'], $searchTextInResponse) == false) {
            $textMessage = $errorMessage ?? "$taskName : Запрашиваемый текст отсутствует";
            if($lastMessage->text != $textMessage)
            {
                $task->messages()->create([
                    'status' => false,
                    'text' => $textMessage,
                    'status_code' => $httpStatusCode
                ]);
            }

            return;

        }

        if ($textPresenceErrorCheck && strpos($httpData['html'], $textPresenceErrorCheck) !== false) {
            $textMessage = $errorMessage ?? "$taskName :Ошибка: текст обнаружен";
            if($lastMessage->text != $textMessage)
            {
                $task->messages()->create([
                    'status' => false,
                    'text' => $textMessage,
                    'status_code' => $httpStatusCode
                ]);
            }
            return;
        }

        if($httpStatusCode != $validResponseCode && $httpStatusCode != $ignoredErrorCodes && $httpStatusCode == $alertOnSpecificCodes)
        {
            $textMessage = $errorMessage ?? "$taskName : Обнаружен ошибочный статус-код в ответе сервера";
            if($lastMessage->text != $textMessage)
            {
                $task->messages()->create([
                    'status' => false,
                    'text' => $textMessage,
                    'status_code' => $httpStatusCode
                ]);
            }
            return;
        }


        if($task->control_domain)
        {
            $this->checkDomainPaidTill($task);
        }

        if($task->site_virus_check)
        {
            $this->checkSiteVirus($url);
        }


    }

    private function kontrolIzmeneniiFailovNaServere($task)
    {
        if(!$task->status)
        {
            return;
        }

        if(!$task->last_check_date)
        {
            $task->last_check_date = now();
            $task->save();
        }

        $givenTime = Carbon::parse($task['last_check_date']);
        $currentTime = Carbon::now();

        if ($givenTime->diffInMinutes($currentTime) >= $task->frequency_of_inspection) {
            $task->last_check_date = now();
            $task->save();
        }else{
            return;
        }

        $url = $task['protocol'] . $task['address_ip'];

        if($port = $task['port'])
        {
            $url .= ":$port";
        }

        $data = $this->httpService->makeHttpCodeRequest($url);

        if($data)
        {
            if(isset($data['newFiles']))
            {
                foreach($data['newFiles'] as $newFile)
                {
                    if(!$this->checkIgnoredDirs($newFile,$task->ignored_directories))
                    {
                        continue;
                    }
                    if (strpos($newFile, 'file_snapshot.json') == false) {
                        $task->messages()->create([
                            'status' => false,
                            'text' => "Создан новый файл $newFile",
                        ]);
                    }
                }
            }

            if(isset($data['deletedFiles']))
            {
                foreach($data['deletedFiles'] as $deletedFile)
                {
                    if(!$this->checkIgnoredDirs($deletedFile,$task->ignored_directories))
                    {
                        continue;
                    }
                    if (strpos($deletedFile, 'file_snapshot.json') == false) {
                        $task->messages()->create([
                            'status' => false,
                            'text' => "Удален файл $deletedFile",
                        ]);
                    }
                }
            }

            if(isset($data['editedFiles']))
            {
                foreach($data['editedFiles'] as $editedFile)
                {
                    if(!$this->checkIgnoredDirs($editedFile,$task->ignored_directories))
                    {
                        continue;
                    }
                    if (strpos($editedFile, 'file_snapshot.json') == false) {
                        $task->messages()->create([
                            'status' => false,
                            'text' => "Отредактирован файл $editedFile",
                        ]);
                    }
                }
            }

        }

    }

    private function checkIgnoredDirs(string $filePath, string $ignoredDirectories): bool
    {
        $ignoredDirs = array_map('trim', explode(',', $ignoredDirectories));

        if (empty($ignoredDirs)) {
            return true;
        }

        foreach ($ignoredDirs as $dir) {
            if (strpos($dir, '.') !== false) {
                if (strpos($filePath, $dir) !== false) {
                    return false;
                }
            } else {
                if (strpos($filePath, "/$dir/") !== false) {
                    return false;
                }
            }
        }

        return true;
    }


    private function checkDomainPaidTill($task)
    {
        $whoisData = $this->whoisService->getDomainInfo('iqmtech.ru');

        if($whoisData && $whoisExtra = $whoisData->getExtra())
        {
            if($whoisExtraGroups = $whoisExtra['rootFilter']->getGroups())
            {
               if(isset($whoisExtraGroups[0]['paid-till']))
               {
                    $whoisPaidTillDate = Carbon::parse($whoisExtraGroups[0]['paid-till']);

                    if($whoisPaidTillDate->isToday())
                    {
                        // send notification
                    }
               }
            }
        }
    }

    private function checkSiteVirus($url)
    {
        $scanWithSucuri = $this->siteScannerService->scanWithSucuri($url);
        $virustotal = $this->siteScannerService->scanWithVirusTotal($url);
    }

    private function proverkaSaitaNaVirusyINalicieVRaznyxBazax($task)
    {
        if($task && $task->status)
        {

            if(!$task->last_check_date)
            {
                $task->last_check_date = now();
                $task->save();
            }

            $givenTime = Carbon::parse($task['last_check_date']);
            $currentTime = Carbon::now();

            if ($givenTime->diffInMinutes($currentTime) >= $task->frequency_of_inspection) {
                $task->last_check_date = now();
                $task->save();
            }else{
                return;
            }

            $url = $task['protocol'] . $task['address_ip'];
            $errorMessage = $task->error_message;

            if($port = $task['port'])
            {
                $url .= ":$port";
            }

            $httpData = $this->httpService->makeSimpleRequest($url,$task);

            $httpStatusCode = $httpData['status'] ?? 500;

            if($httpData && isset($httpData['html']))
            {

                $dangerousSitesDetection = $task->dangerous_sites_detection;

                if($dangerousSitesDetection)
                {
                    $linkArray = $this->extractLinksAndSrcs($httpData['html']);

                    foreach($linkArray as $link)
                    {
                        if(!$this->virusTotalService->checkUrl($link))
                        {
                            $task->messages()->create([
                                'status' => false,
                                'text' => $errorMessage ?? 'Обнаружен вирусный URL ' . $link,
                                'status_code' => $httpStatusCode
                            ]);
                        }
                    }
                }

            }

        }
    }

    public function extractLinksAndSrcs($html): array
    {

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        libxml_clear_errors();

        $links = [];

        foreach ($dom->getElementsByTagName('a') as $element) {
            $href = $element->getAttribute('href');
            if (!empty($href)) {
                $links[] = $href;
            }
        }

        foreach ($dom->getElementsByTagName('link') as $element) {
            $href = $element->getAttribute('href');
            if (!empty($href)) {
                $links[] = $href;
            }
        }

        $tagsWithSrc = ['img', 'script', 'iframe', 'source', 'video', 'audio'];

        foreach ($tagsWithSrc as $tag) {
            foreach ($dom->getElementsByTagName($tag) as $element) {
                $src = $element->getAttribute('src');
                if (!empty($src)) {
                    $links[] = $src;
                }
            }
        }

        return $links;
    }

    public function extractSeoLinksAndSrcs($html): array
    {
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        libxml_clear_errors();

        $links = [];

        // Extract from <a href="">
        foreach ($dom->getElementsByTagName('a') as $element) {
            $href = $element->getAttribute('href');
            if (!empty($href)) {
                $links[] = $href;
            }
        }

        // Extract from <link href="">
        foreach ($dom->getElementsByTagName('link') as $element) {
            $href = $element->getAttribute('href');
            if (!empty($href)) {
                $links[] = $href;
            }
        }

        // Extract from tags with src attribute
        $tagsWithSrc = ['img', 'script', 'iframe', 'source', 'video', 'audio'];
        foreach ($tagsWithSrc as $tag) {
            foreach ($dom->getElementsByTagName($tag) as $element) {
                $src = $element->getAttribute('src');
                if (!empty($src)) {
                    $links[] = $src;
                }
            }
        }

        // Extract from meta tags (Open Graph, Twitter, etc.)
        $metaUrlProps = ['og:image', 'og:url', 'twitter:image', 'twitter:url'];
        foreach ($dom->getElementsByTagName('meta') as $element) {
            $property = $element->getAttribute('property');
            $name = $element->getAttribute('name');
            $content = $element->getAttribute('content');

            if (!empty($content)) {
                if (in_array($property, $metaUrlProps) || in_array($name, $metaUrlProps)) {
                    $links[] = $content;
                }
            }
        }

        return array_values(array_unique($links)); // Remove duplicates and reindex
    }


    private function monitoringNaliciiaSsylokIHtmlKoda($task)
    {
        // if(!$task->status)
        // {
        //     return;
        // }

        // if(!$task->last_check_date)
        // {
        //     $task->last_check_date = now();
        //     $task->save();
        // }

        // $givenTime = Carbon::parse($task['last_check_date']);
        // $currentTime = Carbon::now();

        // if ($givenTime->diffInMinutes($currentTime) >= $task->frequency_of_inspection) {
        //     $task->last_check_date = now();
        //     $task->save();
        // }else{
        //     return;
        // }

        $url = $task['protocol'] . $task['address_ip'];
        $errorMessage = $task->error_message;

        if($port = $task['port'])
        {
            $url .= ":$port";
        }

        $httpData = $this->httpService->makeSimpleRequest($url,$task);

        $httpStatusCode = $httpData['status'] ?? 500;

        if($httpData && isset($httpData['html']))
        {

            $linkArray = $this->extractSeoLinksAndSrcs($httpData['html']);

            foreach($task->links as $link)
            {
                if(!in_array($link->link, $linkArray))
                {
                    $taskName = $task->name;
                    $linkName = $link->link;

                        $task->messages()->create([
                        'status' => false,
                        'text' => $errorMessage ?? "На $taskName отсутствует ссылка на $linkName",
                    ]);
                }
            }

        }
    }


}
