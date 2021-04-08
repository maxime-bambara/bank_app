<?php

namespace App\Tests\Entity;

use App\Entity\Banker;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class BankerTest extends TestCase 
{
    public function getCustomer(){
        return (new User())
        ->setEmail('john.doe@example.com')
        ->setPassword('0000')
        ->setLastName('Doe')
        ->setFirstName('John');
    }

    public function getEntity (){
        return (new Banker())
        ->setUsername('Mr Laurent')
        ->setPassword('0000');
    }

    public function getEntityWithCustomer(){
        $customer = $this->getCustomer();
        $banker = $this->getEntity();
        $banker->addCustomer($customer);
        return $banker;
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
        $this->assertEquals(['ROLE_BANKER'], $this->getEntity()->getRoles());
    }

    public function testNotValidRole(){
        $this->assertNotEquals(['ROLE_ADMIN'], $this->getEntity()->getRoles());
        $this->assertNotEquals(['ROLE_USER'], $this->getEntity()->getRoles());
    }

    public function testNoCustomerInChargeOf(){
        $this->assertEmpty($this->getEntity()->getCustomers());
    }

    public function testValidCustomer(){
        
        $this->assertContainsOnly(User::class,$this->getEntityWithCustomer()->getCustomers()->getValues());
    }

    public function testRealValidCustomer(){
        $this->assertEquals($this->getCustomer()->getEmail(), $this->getEntityWithCustomer()->getCustomers()->getValues()[0]->getEmail());
    }



}