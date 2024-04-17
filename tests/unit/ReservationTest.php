<?php

namespace App\Tests\Unit;

use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Offer;
use App\Entity\Payment;
use PHPUnit\Framework\TestCase;

class ReservationTest extends TestCase
{
    public function testReservation()
    {
        $reservation = new Reservation();
        $user = new User();
        $offer = new Offer();
        $payment = new Payment();

        // Test setNumberOfTicket and getNumberOfTicket
        $reservation->setNumberOfTicket(1);
        $this->assertEquals(1, $reservation->getNumberOfTicket());

        // Test setFirstname and getFirstname
        $reservation->setFirstname('Test Firstname');
        $this->assertEquals('Test Firstname', $reservation->getFirstname());

        // Test setLastname and getLastname
        $reservation->setLastname('Test Lastname');
        $this->assertEquals('Test Lastname', $reservation->getLastname());

        // Test setUser and getUser
        $reservation->setUser($user);
        $this->assertSame($user, $reservation->getUser());

        // Test addOffer, getOffer and removeOffer
        $reservation->addOffer($offer);
        $this->assertCount(1, $reservation->getOffer());
        $this->assertTrue($reservation->getOffer()->contains($offer));

        $reservation->removeOffer($offer);
        $this->assertCount(0, $reservation->getOffer());
        $this->assertFalse($reservation->getOffer()->contains($offer));

        // Test setIsPaid and getIsPaid
        $reservation->setIsPaid(true);
        $this->assertEquals(true, $reservation->getIsPaid());

        // Test setPayment and getPayment
        $reservation->setPayment($payment);
        $this->assertSame($payment, $reservation->getPayment());
    }
}
