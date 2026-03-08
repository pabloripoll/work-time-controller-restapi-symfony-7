<?php

namespace App\Tests\Functional\Presentation\Http\Rest\Admin\Employment;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmploymentWorkdayControllerTest extends WebTestCase
{
    private $client;
    private string $token;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        
        $this->client->request('POST', '/api/v1/admin/auth/login', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'email' => 'admin@example.com',
            'password' => 'password123'
        ]));

        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->token = $response['token'] ?? '';
    }

    public function testListWorkdays(): void
    {
        $employeeId = 1;

        $this->client->request('GET', "/api/v1/admin/employment/contracts/{$employeeId}/workdays", [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $this->token,
            'CONTENT_TYPE' => 'application/json',
        ]);

        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('success', $response['status']);
    }
}
