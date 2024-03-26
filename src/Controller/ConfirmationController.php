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
    #[Route('/confirmation', name: 'app_confirmation')]
    public function index(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        // Récupérez l'utilisateur connecté
        $user = $this->getUser();

        // Récupérez le paiement de l'utilisateur
        $payment = $entityManager->getRepository(Payment::class)->findOneBy(['user' => $user]);

        if (!$payment) {
            throw $this->createNotFoundException('Aucun paiement trouvé pour l\'utilisateur connecté');
        }

        // Récupérez les offres et le nombre de billets de la session
        $offers = $session->get('offers', []);

        // Générez la clé finale
        $finalKey = $this->generateFinalKey($user->getId(), $payment->getId());

        // Générez le QR Code
        $qrCodeImage = $this->generateQrCode($finalKey);

        return $this->render('confirmation/index.html.twig', [
            'controller_name' => 'ConfirmationController',
            'qrCodeImage' => $qrCodeImage,
            'offers' => $offers,
        ]);
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

    #[Route('/confirmation/ticket', name: 'app_confirmation_ticket')]
    public function generatePdfTicket(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        // Récupérez l'utilisateur connecté
        $user = $this->getUser();

        // Récupérez le paiement de l'utilisateur
        $payment = $entityManager->getRepository(Payment::class)->findOneBy(['user' => $user]);

        if (!$payment) {
            throw $this->createNotFoundException('Aucun paiement trouvé pour l\'utilisateur connecté');
        }

        // Récupérez les offres et le nombre de billets de la session
        $offers = $session->get('offers', []);

        // Générez la clé finale
        $finalKey = $this->generateFinalKey($user->getId(), $payment->getId());

        // Générez le QR Code
        $qrCodeImage = $this->generateQrCode($finalKey);

        $html = $this->renderView('confirmation/ticket.html.twig', array(
            'finalKey' => $finalKey,
            'offers' => $offers,
            'qrCodeImage' => $qrCodeImage, 
        ));

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }
}
