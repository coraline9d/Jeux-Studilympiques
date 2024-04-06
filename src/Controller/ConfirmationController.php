<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Payment;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConfirmationController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/confirmation', name: 'app_confirmation')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérez l'utilisateur connecté
        $user = $this->getUser();

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérez tous les paiements de l'utilisateur
        $payments = $entityManager->getRepository(Payment::class)->findBy(['user' => $user]);

        if (!$payments) {
            throw $this->createNotFoundException('Aucun paiement trouvé pour l\'utilisateur connecté');
        }

        // Pour chaque paiement, récupérez les réservations associées
        foreach ($payments as $payment) { 
            // Récupérez les réservations associées au paiement
            $reservations = $payment->getReservations();

            // Initialisez les variables
            $offerDetails = [];

            foreach ($reservations as $reservation) {
                $offers = $reservation->getOffer();
                $numberOfTickets = $reservation->getNumberOfTicket();
    
                // Créez un nouveau tableau pour chaque réservation
                $offerDetailsPerReservation = [];
    
                foreach ($offers as $offer) {
                    $offerName = $offer->getName();
                    $offerDetailsPerReservation[] = ['offerName' => $offerName, 'numberOfTickets' => $numberOfTickets];
                }
    
                // Ajoutez les détails de l'offre de cette réservation au tableau principal
                $offerDetails[] = $offerDetailsPerReservation;
            }
        }

        return $this->render('confirmation/index.html.twig', array(
            'offerDetails' => $offerDetails, 
            'user' => $user,
        ));
    }

    public function generateFinalKey($authKey, $paymentKey)
    {
        $finalKey = $authKey . $paymentKey;
        return $finalKey;
    }

    public function generateQrCode($finalKey)
    {
        // Définissez les options pour le QR code
        $options = new QROptions([
            'version'    => 5,
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'eccLevel'   => QRCode::ECC_L,
        ]);

        // Créez un nouveau QR code avec les options spécifiées
        $qrCode = new QRCode($options);

        // Renvoyez le QR code en tant que chaîne SVG
        return $qrCode->render($finalKey);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/confirmation/ticket', name: 'app_confirmation_ticket')]
    public function generatePdfTicket(EntityManagerInterface $entityManager): Response
    {
        // Récupérez l'utilisateur connecté
        $user = $this->getUser();

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérez tous les paiements de l'utilisateur
        $payments = $entityManager->getRepository(Payment::class)->findBy(['user' => $user]);

        if (!$payments) {
            throw $this->createNotFoundException('Aucun paiement trouvé pour l\'utilisateur connecté');
        }

        // Pour chaque paiement, récupérez les réservations associées
        foreach ($payments as $payment) { 
            // Récupérez les réservations associées au paiement
            $reservations = $payment->getReservations();

            // Initialisez les variables
            $offerDetails = [];

            foreach ($reservations as $reservation) {
                $offers = $reservation->getOffer();
                $numberOfTickets = $reservation->getNumberOfTicket();
    
                // Créez un nouveau tableau pour chaque réservation
                $offerDetailsPerReservation = [];
    
                    foreach ($offers as $offer) {
                        $offerName = $offer->getName();
                        $offerDetailsPerReservation[] = ['offerName' => $offerName, 'numberOfTickets' => $numberOfTickets];
                    }
    
                // Ajoutez les détails de l'offre de cette réservation au tableau principal
                $offerDetails[] = $offerDetailsPerReservation;
            }
        }
    
        // Générez la clé finale
        $finalKey = $this->generateFinalKey($user->getId(), $payment->getId());

        // Générez le QR Code
        $qrCodeImage = $this->generateQrCode($finalKey);

        $html = $this->renderView('confirmation/ticket.html.twig', array(
            'finalKey' => $finalKey,
            'offerDetails' => $offerDetails,
            'qrCodeImage' => $qrCodeImage, 
            'user' => $user,
        ));

        // Configuration de Dompdf 
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Crée une nouvelle instance de Dompdf avec les options spécifiées
        $dompdf = new Dompdf($pdfOptions); 

        // Charge le HTML à convertir en PDF
        $dompdf->loadHtml($html); 
        
        // Définit le format du papier sur lequel le PDF sera imprimé et son orientation
        $dompdf->setPaper('A4', 'portrait'); 
        
        // Génère le PDF à partir du HTML chargé
        $dompdf->render(); 
        
        // Génère un nom de fichier unique pour le PDF
        $filename = uniqid().'.pdf'; 
        
        // Définit le chemin où le PDF sera enregistré
        $pdfPath = $this->getParameter('kernel.project_dir').'/public/pdf/'.$filename; 
        
        // Enregistre le PDF généré dans le chemin spécifié
        file_put_contents($pdfPath, $dompdf->output()); 
        
        // Pour chaque réservation dans la liste des réservations
        foreach ($reservations as $reservation) { 
            // Définit le chemin du PDF comme ticket pour la réservation
            $reservation->setTicket($pdfPath); 
            // Prépare la réservation pour être enregistrée dans la base de données
            $entityManager->persist($reservation); 
        }
        
        // Enregistre toutes les réservations préparées dans la base de données
        $entityManager->flush(); 
        
        // Envoie le PDF généré au navigateur pour être téléchargé
        $dompdf->stream($filename, [ "Attachment" => true ]); 
        
    }
}