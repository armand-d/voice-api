<?php

declare(strict_types=1);

namespace App\Tests\Func;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Faker\Factory;

Class UserTest extends AbstractEndPoint
{
    private string $userPayload = '{
        "email": "%s",
        "roles": [
            "ROLE_USER"
        ],
        "password": "password",
        "lang": "fr",
        "status": "enabled",
        "updatedAt": "2021-02-01T21:42:52.320Z",
        "firstName": "Jhon",
        "lastName": "Doe",
        "accountName": "%s"
    }';

    public function testGetUser (): void
    {
        $response = $this->getResponseFromRequest(Request::METHOD_GET, 'api/users');
        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }
    
    public function testPostUser (): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            'api/users',
            $this->getPayload()
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    private function getPayload (): string
    {
        $faker = Factory::create();

        return sprintf($this->userPayload, $faker->email, $faker->name);
    }
}
