<?php

namespace App\Tests\Unit;

use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Offer;
use PHPUnit\Framework\TestCase;

class ReservationTest extends TestCase
{
    public function testReservation()
    {
        $reservation = new Reservation();
        $user = new User();
        $offer = new Offer();

        $reservation->setNumberOfTicket(1);
        $this->assertEquals(1, $reservation->getNumberOfTicket());

        $reservation->setFirstname('Test Firstname');
        $this->assertEquals('Test Firstname', $reservation->getFirstname());

        $reservation->setLastname('Test Lastname');
        $this->assertEquals('Test Lastname', $reservation->getLastname());

        $reservation->setUser($user);
        $this->assertEquals($user, $reservation->getUser());

        $reservation->addOffer($offer);
        $this->assertCount(1, $reservation->getOffer());

        $reservation->removeOffer($offer);
        $this->assertCount(0, $reservation->getOffer());

        $reservation->setIsPaid(true);
        $this->assertEquals(true, $reservation->getIsPaid());
    }
}

