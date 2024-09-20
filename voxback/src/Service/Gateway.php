<?php

namespace App\Service;

class Gateway extends \Braintree\Gateway
{
    function __construct(
        string $environment,
        string $merchantId,
        string $publicKey,
        string $privateKey
    ) {
        parent::__construct([
            'environment' => $environment,
            'merchantId' => $merchantId,
            'publicKey' => $publicKey,
            'privateKey' => $privateKey
        ]);
    }
}
