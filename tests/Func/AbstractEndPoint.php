<?php

declare(strict_types=1);

namespace App\Tests\Func;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract Class AbstractEndPoint extends WebTestCase
{
    private array $serverInformations = ['ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json'];

    public function getResponseFromRequest (string $method, string $uri, string $payload = ''): Response
    {
        $client = self::createClient();

        $client->request(
            $method, 
            $uri . '.json',
            [],
            [],
            $this->serverInformations,
            $payload,
            true
        );

        return $client->getResponse();
    }
}