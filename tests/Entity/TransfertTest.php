<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Beneficiary;
use App\Entity\User;
use App\Entity\Transfert;

class TransfertTest extends TestCase
{
    public function getSender(){
        return (new User())
        ->setEmail('john.doe@example.com')
        ->setPassword('0000')
        ->setLastName('Doe')
        ->setFirstName('John');
    }


    public function getBeneficiary(){
        return(new Beneficiary())
        ->setLastName('Dupont')
        ->setFirstName('Jean')
        ->setWording('Papa')
        ->setIban('FR5110096000707328477364Y64');
    }

    public function getEntity(){
        return (new Transfert())
        ->setAmount(2000);
    }

    public function getEntityWithBeneficiary(){
        $transfert = $this->getEntity();
        $beneficiary = $this->getBeneficiary();
        $transfert->setBeneficiary($beneficiary);
        return $transfert;
    }

    public function getEntityWithSender(){
        $transfert = $this->getEntity();
        $sender = $this->getSender();
        $transfert->setSender($sender);
        return $transfert;
    }

    public function testAmount(){
        $this->assertEquals(2000, $this->getEntity()->getAmount());
    }

    public function testEmptySender(){
        $this->assertEquals(null, $this->getEntity()->getSender());
    }

    public function testEmptyBeneficiary(){
        $this->assertEquals(null, $this->getEntity()->getBeneficiary());
    }

    public function testValidBeneficiary(){
        $this->assertEquals($this->getBeneficiary(),$this->getEntityWithBeneficiary()->getBeneficiary());
    }

    public function testValidSender(){
        $this->assertEquals($this->getSender(),$this->getEntityWithSender()->getSender());
    }
}