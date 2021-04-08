<?php

namespace App\Entity\Tests;

use App\Entity\Admin;
use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase
{
    public function getEntity (){
        return (new Admin())
        ->setUsername('Mr Laurent')
        ->setPassword('0000');
    }

    public function testValidUsername (){
        $this->assertEquals('Mr Laurent', $this->getEntity()->getUsername());
    }

    public function testNotValidUsername(){
        $this->assertNotEquals('Mr Laurent', $this->getEntity()->setPassword('0000')->getPassword());
    }

    public function testValidPassword(){
        $this->assertEquals('0000', $this->getEntity()->getPassword());
    }

    public function testNotValidPassword(){
        $this->assertNotEquals('0000', $this->getEntity()->setUsername('0001')->getUsername());
    }


    public function testValidRole(){
        $this->assertEquals(['ROLE_ADMIN'], $this->getEntity()->getRoles());
    }

    public function testNotValidRole(){
        $this->assertNotEquals(['ROLE_BANKER'], $this->getEntity()->getRoles());
        $this->assertNotEquals(['ROLE_USER'], $this->getEntity()->getRoles());
    }
}
