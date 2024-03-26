<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Form\PaymentType;
use App\Repository\OfferRepository;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/payment')]
class PaymentController extends AbstractController
{
    #[Route('/', name: 'app_payment_index', methods: ['GET'])]
    public function index(PaymentRepository $paymentRepository): Response
    {
        return $this->render('payment/index.html.twig', [
            'payments' => $paymentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_payment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SessionInterface $session, OfferRepository $offerRepository): Response
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser(); 

        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Association de l'utilisateur au paiement
            $payment->setUser($user);

            // Récupération des réservations dans le panier
            $reservationsInCart = $session->get('reservations', []);

            // Initialisation de la variable $offers
            $offers = [];

            foreach ($reservationsInCart as $reservationInCart) {
                // Récupération de l'offre associée à la réservation
                $offer = $offerRepository->find($reservationInCart['offerId']);

                // Mise à jour du compteur de l'offre
                $numberOfTickets = $reservationInCart['reservation']->getNumberOfTicket();
                $offer->setCounter($offer->getCounter() + $numberOfTickets);

                // Stockage de l'offre et du nombre de billets dans la session
            $offers[] = ['offer' => $offer, 'numberOfTickets' => $numberOfTickets];

                // Supprimer la réservation de la session
                unset($reservationsInCart[array_search($reservationInCart, $reservationsInCart)]);
            }
    
            $session->set('offers', $offers);
            $session->set('reservations', $reservationsInCart);
            $entityManager->persist($payment);
            $entityManager->flush();

            return $this->redirectToRoute('app_confirmation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('payment/new.html.twig', [
            'payment' => $payment,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_payment_show', methods: ['GET'])]
    public function show(Payment $payment): Response
    {
        return $this->render('payment/show.html.twig', [
            'payment' => $payment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_payment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Payment $payment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_payment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('payment/edit.html.twig', [
            'payment' => $payment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payment_delete', methods: ['POST'])]
    public function delete(Request $request, Payment $payment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$payment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($payment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_payment_index', [], Response::HTTP_SEE_OTHER);
    }
}
