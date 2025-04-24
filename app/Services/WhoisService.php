<?php

namespace App\Services;

use Iodev\Whois\Factory;

class WhoisService
{
    public function getDomainInfo(string $domain)
    {
        $whois = Factory::get()->createWhois();
        $info = $whois->loadDomainInfo($domain);

        if (!$info) {
            return null;
        }
        return $info;
    }
}
