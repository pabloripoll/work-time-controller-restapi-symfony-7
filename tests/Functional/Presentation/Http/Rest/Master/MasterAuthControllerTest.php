<?php

namespace App\Tests\Functional\Presentation\Http\Rest\Master;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MasterAuthControllerTest extends WebTestCase
{
    private $client;
    private string $token;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        
        // Login as master to get token
        $this->client->request('POST', '/api/v1/master/auth/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'email' => 'master@webmaster.com',
            'password' => 'password123'
        ]));

        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->token = $response['token'] ?? '';
    }

    public function testGetProfile(): void
    {
        $this->client->request('GET', '/api/v1/master/account/profile', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
            'CONTENT_TYPE' => 'application/json',
        ]);

        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('data', $response);
    }

    public function testUpdateProfile(): void
    {
        $this->client->request('PATCH', '/api/v1/master/account/settings/profile', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'nickname' => 'updated_master'
        ]));

        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('success', $response['status']);
    }
}
