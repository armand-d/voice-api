<?php

declare(strict_types=1);

namespace App\Tests\Func;

use App\DataFixtures\AppFixtures;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractEndPoint extends WebTestCase
{
    protected array $serverInformations = ['ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json'];
    protected string $tokenNotFound = 'JWT token not found';
    protected string $notYoutResource = "It's not your resource";
    protected string $loginPayload = '{"username": "%s", "password": "%s"}';

    public function getResponseFromRequest (
        string $method,
        string $uri,
        string $payload = '',
        array $parameter = [],
        bool $withAuthentification = true
    ): Response {
        $client = $this->createAuthentificationClient($withAuthentification);

        $client->request(
            $method,
            $uri . '.json',
            $parameter,
            [],
            $this->serverInformations,
            $payload,
            true
        );

        return $client->getResponse();
    }

    protected function createAuthentificationClient(bool $withAuthentification) :KernelBrowser
    {
        $client = static::createClient();

        if (!$withAuthentification) {
            return $client;
        }

        $client->request(
            Request::METHOD_POST,
            '/api/login_check',
            [],
            [],
            $this->serverInformations,
            sprintf($this->loginPayload, AppFixtures::DEFAULT_USER['username'], AppFixtures::DEFAULT_USER['password']),
            true
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameter('HTTP_authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }
}