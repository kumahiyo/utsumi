<?php

namespace Plugin\EccubePaymentLite4\Service\GmoEpsilonRequest;

use GuzzleHttp\Client;
use Plugin\EccubePaymentLite4\Service\GmoEpsilonRequestService;

class RequestCard3Service
{
    /**
     * @var GmoEpsilonRequestService
     */
    private $gmoEpsilonRequestService;

    public function __construct(
        GmoEpsilonRequestService $gmoEpsilonRequestService
    ) {
        $this->gmoEpsilonRequestService = $gmoEpsilonRequestService;
    }

    public function send(string $url, bool $dontRedirect = true): bool
    {
        $client = new Client();
        $Response = $client->get($url, ['allow_redirects' => !$dontRedirect]);

        $statusCode = $Response->getStatusCode();
        return $statusCode === 200 || ($dontRedirect && $statusCode === 302);
    }
}
