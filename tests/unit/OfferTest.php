<?php

namespace App\Tests\Unit;

use App\Entity\Offer;
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
}
