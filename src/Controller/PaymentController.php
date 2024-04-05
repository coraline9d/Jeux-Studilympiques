<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Form\PaymentType;
use App\Repository\OfferRepository;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/payment')]
class PaymentController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/new', name: 'app_payment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, OfferRepository $offerRepository, ReservationRepository $reservationRepository): Response
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser(); 

        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Association de l'utilisateur au paiement
            $payment->setUser($user);

            // Récupération des réservations de l'utilisateur depuis la base de données
            $reservations = $reservationRepository->findBy(['user' => $user, 'isPaid' => false]);

            foreach ($reservations as $reservation) {
                // Récupération des offres associées à la réservation
                $offers = $reservation->getOffer();

                foreach ($offers as $offer) {
                    // Mise à jour du compteur de l'offre
                    $offer->setCounter($offer->getCounter() + $reservation->getNumberOfTicket());

                    // Persister l'offre modifiée
                    $entityManager->persist($offer);
                }

                // Association du paiement à la réservation
                $reservation->setPayment($payment);

                // Mise à jour de l'état de paiement de la réservation
                $reservation->setIsPaid(true);

                // Dire à Doctrine de gérer (persist) l'entité Reservation
                $entityManager->persist($reservation);
            }

            $entityManager->persist($payment);
            $entityManager->flush();

            return $this->redirectToRoute('app_confirmation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('payment/new.html.twig', [
            'payment' => $payment,
            'form' => $form->createView(),
        ]);
    }
}
