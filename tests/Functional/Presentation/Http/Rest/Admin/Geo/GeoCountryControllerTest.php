<?php

namespace App\Tests\Functional\Presentation\Http\Rest\Admin\Geo;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GeoCountryControllerTest extends WebTestCase
{
    private $client;
    private string $token;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        
        // Login as admin
        $this->client->request('POST', '/api/v1/admin/auth/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'email' => 'admin@example.com',
            'password' => 'password123'
        ]));

        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->token = $response['token'] ?? '';
    }

    public function testListCountries(): void
    {
        $this->client->request('GET', '/api/v1/admin/geo/countries', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
            'CONTENT_TYPE' => 'application/json',
        ]);

        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('success', $response['status']);
        $this->assertArrayHasKey('data', $response);
    }

    public function testGetCountry(): void
    {
        $this->client->request('GET', '/api/v1/admin/geo/countries/1', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
            'CONTENT_TYPE' => 'application/json',
        ]);

        $this->assertResponseIsSuccessful();
    }
}
