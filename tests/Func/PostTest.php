<?php

declare(strict_types=1);

namespace App\Tests\Func;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostTest extends AbstractEndPoint
{
    public function testGetPosts(): array
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/posts',
            '',
            [],
            false
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);
// dd($responseDecoded);
        Self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        Self::assertJson($responseContent);
        Self::assertNotEmpty($responseDecoded);

        return $responseDecoded;
    }

    /**
     * @param array $res
     * @return void
     * @throws \Exception
     * @depends testGetPosts
     */
    public function testGetPost(array $res): void
    {
        if (0 === count($res)) {
            throw new \Exception("Use this command => bin/console d:f:l (no data found)");
        }
        $response = $this->getResponseFromRequest(
            Request::METHOD_GET,
            '/api/posts/'.$res[0]->id,
            '',
            [],
            false
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent, true);

        Self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        Self::assertJson($responseContent);
        Self::assertNotEmpty($responseDecoded);
        Self::assertNotSame($res[0], $responseDecoded);
        Self::assertStringContainsString("author", $responseContent);
    }
}
