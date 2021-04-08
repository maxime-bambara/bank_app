<?php

namespace App\Tests\Entity;

use App\Entity\Banker;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BankerTest extends KernelTestCase 
{

    public function getEntity (){
        return (new Banker())
        ->setUsername('Mr Laurent')
        ->setPassword('0000');
    }

    public function assertHasErrors (Banker $banker, int $number=0){
        self::bootKernel();
        $error = self::$container->get('validator')->validate($banker);
        $this->assertCount($number, $error);
    }

    public function testValidBanker(){
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testBlankUsernameBanker(){
        $this->assertHasErrors($this->getEntity()->setUsername(''), 2);
    }

    public function testSizeUsernameBanker(){
        $this->assertHasErrors($this->getEntity()->setUsername('a'), 1);
        $this->assertHasErrors($this->getEntity()->setUsername('ezubcuzfeuozefuejdzcufehzeihzduoefjlhiouhfehledizdhefzoihizefhhfezuihefiozhjifhe'), 1);
    }

    public function testRole(){
        $this->assertEquals(['ROLE_BANKER'], $this->getEntity()->getRoles());
        $this->assertNotEquals(['ROLE_ADMIN'], $this->getEntity()->getRoles());
        $this->assertNotEquals(['ROLE_USER'], $this->getEntity()->getRoles());

    }
}