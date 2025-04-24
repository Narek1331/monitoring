<?php

namespace App\Services;

use Carbon\Carbon;

class CheckService
{
    protected $taskService;
    protected $httpService;
    protected $whoisService;
    protected $siteScannerService;

    public function __construct(
        TaskService $taskService,
        HttpService $httpService,
        WhoisService $whoisService,
        SiteScannerService $siteScannerService
        )
    {
        $this->taskService = $taskService;
        $this->httpService = $httpService;
        $this->whoisService = $whoisService;
        $this->siteScannerService = $siteScannerService;
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

        if($port = $task['port'])
        {
            $url .= ":$port";
        }

        $httpData = $this->httpService->makeSimpleRequest($url,$task);

        $httpStatusCode = $httpData['status'] ?? 500;

        if(!$httpData)
        {
            $task->messages()->create([
                'status' => false,
                'text' => $errorMessage ?? 'Ошибка сервера',
                'status_code' => 500
            ]);
            return;
        }

        if($notifyOnRecovery && $lastMessage = $task->messages->last())
        {
            if(!$lastMessage->status)
            {
                $task->messages()->create([
                    'status' => true,
                    'text' => 'Сервер полностью восстановлен',
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
            $task->messages()->create([
                'status' => false,
                'text' => $errorMessage ?? 'Запрашиваемый текст отсутствует',
                'status_code' => $httpStatusCode
            ]);
            return;

        }

        if ($textPresenceErrorCheck && strpos($httpData['html'], $textPresenceErrorCheck) !== false) {
            $task->messages()->create([
                'status' => false,
                'text' => $errorMessage ?? 'Ошибка: текст обнаружен',
                'status_code' => $httpStatusCode
            ]);
            return;
        }

        if($httpStatusCode != $validResponseCode && $httpStatusCode != $ignoredErrorCodes && $httpStatusCode == $alertOnSpecificCodes)
        {
            $task->messages()->create([
                'status' => false,
                'text' => $errorMessage ?? 'Обнаружен ошибочный статус-код в ответе сервера',
                'status_code' => $httpStatusCode
            ]);
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


}
