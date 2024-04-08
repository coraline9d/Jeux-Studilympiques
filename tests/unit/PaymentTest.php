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

        $payment->setUser($user);
        $this->assertEquals($user, $payment->getUser());

        $payment->addReservation($reservation);
        $this->assertCount(1, $payment->getReservations());

        $payment->removeReservation($reservation);
        $this->assertCount(0, $payment->getReservations());
    }
}
