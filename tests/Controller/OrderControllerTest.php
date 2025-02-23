<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    public function testCreateOrder()
    {
        $client = static::createClient();
        $client->request('POST', '/order', [
            'order_number' => '12345',
            'date' => '2023-10-10',
            'total_amount' => 100.50
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Order created successfully', $client->getResponse()->getContent());
    }

    public function testReadOrder()
    {
        $client = static::createClient();
        $client->request('GET', '/order/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testUpdateOrder()
    {
        $client = static::createClient();
        $client->request('PUT', '/order/1', [
            'order_number' => '54321',
            'date' => '2023-11-11',
            'total_amount' => 200.75
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Order updated successfully', $client->getResponse()->getContent());
    }

    public function testDeleteOrder()
    {
        $client = static::createClient();
        $client->request('DELETE', '/order/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Order deleted successfully', $client->getResponse()->getContent());
    }
}
