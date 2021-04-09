<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Beneficiary;
use App\Entity\User;
use App\Entity\Transfert;

class BeneficiaryTest extends TestCase
{
    public function getSender(){
        return (new User())
        ->setEmail('john.doe@example.com')
        ->setPassword('0000')
        ->setLastName('Doe')
        ->setFirstName('John');
    }

    public function getTransfert(){
        return (new Transfert())
        ->setAmount(2000);
    }

    public function getEntity(){
        return(new Beneficiary())
        ->setLastName('Dupont')
        ->setFirstName('Jean')
        ->setWording('Papa')
        ->setIban('FR5110096000707328477364Y64');
    }

    public function getEntityWithSender(){
        $beneficiary = $this->getEntity();
        $sender = $this->getSender();
        $beneficiary->setSender($sender);
        return $beneficiary;
    }

    public function getEntityWithTransferts(){
        $beneficiary = $this->getEntity();
        $transfert = $this->getTransfert();
        $beneficiary->addTransfert($transfert);
        return $beneficiary;
    }

    public function testValidLastName(){
        $this->assertEquals('Dupont', $this->getEntity()->getLastName());
    }

    public function testValidFirstName(){
        $this->assertEquals('Jean', $this->getEntity()->getFirstName());
    }

    public function testWording(){
        $this->assertEquals('Papa', $this->getEntity()->getWording());
    }

    public function testIban(){
        $this->assertEquals('FR5110096000707328477364Y64', $this->getEntity()->getIban());
    }

    public function testSenderIsEmpty(){
        $this->assertEquals(null,$this->getEntity()->getSender());
    }

    public function testValidSender(){
        $this->assertEquals($this->getSender(),$this->getEntityWithSender()->getSender());
    }

    public function testEmptyTransfertCollection(){
        $this->assertEmpty($this->getEntity()->getTransferts());
    }

    public function testValidTransferts(){
        
        $this->assertContainsOnly(Transfert::class,$this->getEntityWithTransferts()->getTransferts()->getValues());
    }

    public function testRealValidTransfert(){
        $this->assertEquals($this->getTransfert()->getAmount(), $this->getEntityWithTransferts()->getTransferts()->getValues()[0]->getAmount());
    }
}