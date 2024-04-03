<?php

namespace App\Controller;

use App\Entity\Offer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OfferController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/offre', name: 'app_offer')]
    public function offer(ManagerRegistry $managerRegistry): Response
    {
        $offer = $this->entityManager
            ->getRepository(Offer::class)
            ->findAll();

        return $this->render('offer/index.html.twig', [
            'offer' => $offer,
        ]);
    } 

    #[Route('/', name: 'app_home')]
    public function home(ManagerRegistry $managerRegistry): Response
    {
        $offer = $managerRegistry->getRepository(Offer::class);

        $solo = $offer->findOneBy(['name' => 'Offre Solo - Pass Olympique Individuel']);
        $duo = $offer->findOneBy(['name' => 'Offre Duo - Pass Olympique en Duo']);
        $family = $offer->findOneBy(['name' => 'Offre Familiale - Pass Olympique pour Toute la Famille']);
        return $this->render('home/index.html.twig', [
            'solo' => $solo,
            'duo' => $duo,
            'family' => $family,
        ]);
    }

    // #[Route('/')]
    // public function index(EntityManagerInterface $entityManager): Response
    // {
    //     $offer = new Offer();

    //     $offer
    //         ->setName('Offre Familiale - Pass Olympique pour Toute la Famille')
    //         ->setDescription('Créez des souvenirs inoubliables en famille avec notre Pass Olympique pour Toute la Famille. Quatre billets offrant un accès complet à tous les aspects des Jeux, pour une expérience enrichissante à partager ensemble.')
    //         ->setPrice(320);

    //     $entityManager->persist($offer);
    //     $entityManager->flush();

    //     return $this->render('offer/index.html.twig', [
    //         'controller_name' => 'OfferController',
    //     ]);
    // }
}
