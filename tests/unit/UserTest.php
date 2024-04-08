<?php

namespace App\Tests\Unit;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUser()
    {
        $user = new User();

        $user->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $user->getEmail());

        $user->setRoles(['ROLE_ADMIN']);
        $this->assertContains('ROLE_ADMIN', $user->getRoles());

        $user->setPassword('password');
        $this->assertEquals('password', $user->getPassword());

        $user->setFirstname('Firstname');
        $this->assertEquals('Firstname', $user->getFirstname());

        $user->setLastname('Lastname');
        $this->assertEquals('Lastname', $user->getLastname());
    }
}
