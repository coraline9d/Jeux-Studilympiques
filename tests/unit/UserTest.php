<?php

namespace App\Tests\Unit;

use App\Entity\User;
use App\Entity\Payment;
use App\Entity\Reservation;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUser()
    {
        $user = new User();
        $payment = new Payment();
        $reservation = new Reservation();

        // Test setEmail and getEmail
        $user->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $user->getEmail());

        // Test setRoles and getRoles
        $user->setRoles(['ROLE_ADMIN']);
        $this->assertContains('ROLE_ADMIN', $user->getRoles());

        // Test setPassword and getPassword
        $user->setPassword('password');
        $this->assertEquals('password', $user->getPassword());

        // Test setFirstname and getFirstname
        $user->setFirstname('Firstname');
        $this->assertEquals('Firstname', $user->getFirstname());

        // Test setLastname and getLastname
        $user->setLastname('Lastname');
        $this->assertEquals('Lastname', $user->getLastname());

        // Test addPayment, getPayments and removePayment
        $user->addPayment($payment);
        $this->assertCount(1, $user->getPayments());
        $this->assertTrue($user->getPayments()->contains($payment));

        $user->removePayment($payment);
        $this->assertCount(0, $user->getPayments());
        $this->assertFalse($user->getPayments()->contains($payment));

        // Test addReservation, getReservations and removeReservation
        $user->addReservation($reservation);
        $this->assertCount(1, $user->getReservations());
        $this->assertTrue($user->getReservations()->contains($reservation));

        $user->removeReservation($reservation);
        $this->assertCount(0, $user->getReservations());
        $this->assertFalse($user->getReservations()->contains($reservation));
    }
}
