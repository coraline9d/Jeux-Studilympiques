<?php

namespace App\Tests\Unit;

use App\Entity\Offer;
use App\Entity\Reservation;
use PHPUnit\Framework\TestCase;

class OfferTest extends TestCase
{
    public function testOffer()
    {
        $offer = new Offer();

        $offer->setName('Test Name');
        $this->assertEquals('Test Name', $offer->getName());

        $offer->setDescription('Test Description');
        $this->assertEquals('Test Description', $offer->getDescription());

        $offer->setPrice(123.45);
        $this->assertEquals(123.45, $offer->getPrice());

        $offer->setCounter(10);
        $this->assertEquals(10, $offer->getCounter());
    }

    public function testReservations()
    {
        $offer = new Offer();
        $reservation = new Reservation();

        // Ajouter une réservation à l'offre
        $offer->addReservation($reservation);

        $this->assertCount(1, $offer->getReservations());
        $this->assertTrue($offer->getReservations()->contains($reservation));

        // Supprimer la réservation de l'offre
        $offer->removeReservation($reservation);

        $this->assertCount(0, $offer->getReservations());
        $this->assertFalse($offer->getReservations()->contains($reservation));
    }
}
