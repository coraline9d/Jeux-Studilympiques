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
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();
    
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
    
        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_login');
        }
    
        // Récupération des réservations non payées de l'utilisateur connecté
        $reservations = $reservationRepository->findBy(['isPaid' => false, 'user' => $user]);
    
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }    

    #[IsGranted('ROLE_USER')]
    #[Route('/nouvelle/{offerId?}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, OfferRepository $offerRepository, $offerId = null): Response
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_login');
        }

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
     
        // Création d'une collection avec l'offre
        $offers = new ArrayCollection();
        $offers->add($offer);

        // Ajout des offres à la réservation
        foreach ($offers as $offer) {
            $reservation->addOffer($offer);
        }


        $form = $this->createForm(ReservationType::class, $reservation, [
        'selected_offer' => [$offer], // Passer l'offre sélectionnée comme une option au formulaire
        ]);;
     
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Association de l'utilisateur à la réservation
            $reservation->setUser($user);

            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/edition', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        
        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupération de l'offre actuelle de la réservation
        $offer = $reservation->getOffer();
    
        // Création du formulaire avec l'offre actuelle comme option
        $form = $this->createForm(ReservationType::class, $reservation, [
            'selected_offer' => $offer,
        ]);
        $form->handleRequest($request); 
    
        // Gestion de la soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();    
    
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }
        
    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/update-tickets', name: 'app_reservation_update_tickets', methods: ['POST'])]
    public function updateTickets(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $numberOfTicket = $request->request->get('numberOfTicket');
        $reservation->setNumberOfTicket($numberOfTicket);
        $entityManager->flush();

        return $this->redirectToRoute('app_reservation_index');
    }
     
    #[IsGranted('ROLE_USER')]
    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager, ReservationRepository $reservationRepository): Response
    {
        // Vérification du jeton CSRF
        if (!$this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            // Retourner une réponse d'erreur ou rediriger si le jeton CSRF est invalide
            // Par exemple, return new Response('Invalid CSRF Token', Response::HTTP_BAD_REQUEST);
            // ou return $this->redirectToRoute('app_reservation_index');
        }

        // Début de la transaction
        $entityManager->beginTransaction();
        try {
            // Suppression des offres associées à la réservation
            foreach ($reservation->getOffer() as $offer) {
                $reservation->removeOffer($offer);
                // Pas besoin de persister l'offre ici car elle sera automatiquement supprimée de la base de données
            }

            // Suppression de la réservation
            $entityManager->remove($reservation);
            $entityManager->flush(); // Exécution de la transaction

            // Appel de la méthode getTotalCost pour obtenir le coût total après la suppression
            $totalCost = $this->getTotalCost($reservationRepository)->getContent();


            // Validation de la transaction
            $entityManager->commit();

            // Retourner une réponse JSON indiquant le succès de la suppression et le coût total
            return new JsonResponse(['status' => 'success', 'totalCost' => $totalCost]);
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction et renvoyer une réponse d'erreur
            $entityManager->rollback();
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/total-cost', name: 'reservation_total_cost', methods: ['GET'])]
    public function getTotalCost(ReservationRepository $reservationRepository): Response
    {
         // Récupération de l'utilisateur connecté
         $user = $this->getUser();
         
        // Récupérez toutes les réservations non payées
        $reservations = $reservationRepository->findBy(['isPaid' => false, 'user' => $user]);

        $totalCost = 0;

        // Calculez le coût total
        foreach ($reservations as $reservation) {
            foreach ($reservation->getOffer() as $offer) {
                $totalCost += $offer->getPrice() * $reservation->getNumberOfTicket();
            }
        }

        // Renvoyez le coût total en tant que réponse JSON
        return new JsonResponse($totalCost);
    }
}
