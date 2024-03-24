<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository, SessionInterface $session): Response
    {
        // Récupération de l'heure actuelle
        $now = new \DateTime();

        // Récupération de l'heure qu'il était il y a 15 minutes
        $fifteenMinutesAgo = (clone $now)->modify('-15 minutes');

        // Récupération des réservations de la session
        $reservationsInCart = $session->get('reservations', []);

        // Filtrage des réservations ajoutées dans les 15 dernières minutes
        $reservations = array_filter($reservationsInCart, function ($reservation) use ($fifteenMinutesAgo) {
            return $reservation['timeAdded'] > $fifteenMinutesAgo;
        });

        // Mise à jour des réservations dans la session
        $session->set('reservations', $reservations);

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/new/{offerId?}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, OfferRepository $offerRepository, $offerId = null, SessionInterface $session): Response
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();

        if ($offerId === null) {
            // Redirigez l'utilisateur vers la page de sélection d'une offre
            return $this->redirectToRoute('app_offer');
        } 

        // Récupération de l'offre sélectionnée
        $offer = $offerRepository->find($offerId);

        $reservation = new Reservation();
        
        // Pré-remplissage du nom et du prénom de l'utilisateur
        $reservation->setFirstname($user->getFirstname());
        $reservation->setLastname($user->getLastname());

        $form = $this->createForm(ReservationType::class, $reservation, [
            'selected_offer' => $offer, // Passer l'offre sélectionnée comme une option au formulaire
        ]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Association de l'utilisateur à la réservation
            $reservation->setUser($user);
        
            $entityManager->persist($reservation);
            $entityManager->flush();
        
            // Ajout de la réservation à la session
            $reservationsInCart = $session->get('reservations', []);
            $reservationsInCart[] = [
                'reservation' => $reservation,
                'timeAdded' => new \DateTime(),
            ];
            $session->set('reservations', $reservationsInCart);
        
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
