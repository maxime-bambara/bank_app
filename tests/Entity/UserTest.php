<?php 

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Transfert;
use App\Entity\Beneficiary;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase 
{
    public function getEntity(){
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

    public function getTransfert(){
        return (new Transfert())
        ->setAmount(2000);
    }

    public function testValidEmail (){
        $this->assertEquals('john.doe@example.com', $this->getEntity()->getUsername());
    }

    public function testNotValidEmail(){
        $this->assertNotEquals('jane.doe@example.com', $this->getEntity()->setEmail('john.doe@example.com')->getEmail());
    }

    public function testValidPassword(){
        $this->assertEquals('0000', $this->getEntity()->getPassword());
    }

    public function testNotValidPassword(){
        $this->assertNotEquals('0000', $this->getEntity()->setPassword('0001')->getPassword());
    }


    public function testValidRole(){
        $this->assertEquals(['ROLE_USER'], $this->getEntity()->getRoles());
    }

    public function testNotValidRole(){
        $this->assertNotEquals(['ROLE_ADMIN'], $this->getEntity()->getRoles());
        $this->assertNotEquals(['ROLE_BANKER'], $this->getEntity()->getRoles());
    }

    public function testDefaultAmount(){
        $this->assertEquals(3000, $this->getEntity()->getAccount());
    }
}