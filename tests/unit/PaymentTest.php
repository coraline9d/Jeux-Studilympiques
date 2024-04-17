<?php

namespace App\Tests\Unit;

use App\Entity\Payment;
use App\Entity\User;
use App\Entity\Reservation;
use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{
    public function testPayment()
    {
        $payment = new Payment();
        $user = new User();
        $reservation = new Reservation();

        // Test setUser and getUser
        $payment->setUser($user);
        $this->assertSame($user, $payment->getUser());

        // Test addReservation and getReservations
        $payment->addReservation($reservation);
        $this->assertCount(1, $payment->getReservations());
        $this->assertTrue($payment->getReservations()->contains($reservation));

        // Test removeReservation
        $payment->removeReservation($reservation);
        $this->assertCount(0, $payment->getReservations());
        $this->assertFalse($payment->getReservations()->contains($reservation));
    }
}
