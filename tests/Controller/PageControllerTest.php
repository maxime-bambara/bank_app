<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PageControllerTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testLoginPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testRegisterPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testBankerLoginPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/banker/login');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    
}
