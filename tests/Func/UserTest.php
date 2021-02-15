<?php

declare(strict_types=1);

namespace App\Tests\Func;

use App\DataFixtures\AppFixtures;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Faker\Factory;

class UserTest extends AbstractEndPoint
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
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/users',
            '',
            [],
            false
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    public function testPostUser (): int
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            '/api/users',
            $this->getPayload(),
            [],
            false
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);

        return $responseDecoded->id;
    }

    public function testPostDefaultUser (): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            '/api/users',
            json_encode(AppFixtures::DEFAULT_USER),
            [],
            false
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_CREATED || Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    public function testGetDefaultUSer(): int
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/users',
            '',
            ['email' => AppFixtures::DEFAULT_USER['email']],
            false
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);

        return $responseDecoded[0]['id'];
    }

    /**
     * @depends testGetDefaultUser
     *
     * @return void
     */
    public function testPutDefaultUser (int $id): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_PUT,
            '/api/users/' . $id,
            $this->getPayload(),
            [],
            false
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @depends testGetDefaultUser
     *
     * @return void
     */
    public function testPatchDefaultUser (int $id): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_PATCH,
            '/api/users/' . $id,
            $this->getPayload(),
            [],
            false
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @depends testGetDefaultUser
     *
     * @return void
     */
    public function testDeleteDefaultUser(int $id): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_DELETE,
            '/api/users/' . $id,
            $this->getPayload(),
            [],
            false
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    public function testPostSameDefaultUser (): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            '/api/users',
            json_encode(AppFixtures::DEFAULT_USER),
            [],
            false
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    /**
     * @depends testGetDefaultUser
     */
    public function testDeleteDefaultUserWithJWT (int $id)
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_DELETE,
            '/api/users/' . $id,
            '',
            [],
            true
        );

        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    /**
     * @depends testPostUser
     */
    public function testDeleteOthertUserWithJWT (int $id)
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_DELETE,
            '/api/users/' . $id,
            '',
            [],
            true
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
        self::assertEquals($this->notYoutResource, $responseDecoded['message']);
    }

    private function getPayload (): string
    {
        $faker = Factory::create();

        return sprintf($this->userPayload, $faker->email, $faker->name);
    }
}
