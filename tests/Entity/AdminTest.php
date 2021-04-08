<?php

namespace App\Entity\Tests;

use App\Entity\Admin;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AdminTest extends KernelTestCase
{
    public function getEntity (){
        return (new Admin())
        ->setUsername('Mr Laurent')
        ->setPassword('0000');
    }

    public function assertHasErrors (Admin $admin, int $number=0){
        self::bootKernel();
        $error = self::$container->get('validator')->validate($admin);
        $this->assertCount($number, $error);
    }

    public function testValidAdmin(){
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testBlankUsernameAdmin(){
        $this->assertHasErrors($this->getEntity()->setUsername(''), 2);
    }

    public function testSizeUsernameAdmin(){
        $this->assertHasErrors($this->getEntity()->setUsername('a'), 1);
        $this->assertHasErrors($this->getEntity()->setUsername('ezubcuzfeuozefuejdzcufehzeihzduoefjlhiouhfehledizdhefzoihizefhhfezuihefiozhjifhe'), 1);
    }

    public function testRole(){
        $this->assertEquals(['ROLE_ADMIN'], $this->getEntity()->getRoles());
        $this->assertNotEquals(['ROLE_BANKER'], $this->getEntity()->getRoles());
        $this->assertNotEquals(['ROLE_USER'], $this->getEntity()->getRoles());

    }
}
