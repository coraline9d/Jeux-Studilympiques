<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RGPDController extends AbstractController
{
    #[Route('/politique-de-confidentialité', name: 'app_rgpd')]
    public function politique(): Response
    {
        return $this->render('RGPD/politique.html.twig', []);
    }

    #[Route('/conditions-générales-d-utilisations', name: 'app_cgu')]
    public function cgu(): Response
    {
        return $this->render('RGPD/CGU.html.twig', []);
    }

    #[Route('/mentions-légales', name: 'app_mentions')]
    public function mentions(): Response
    {
        return $this->render('RGPD/mentions.html.twig', []);
    }

    #[Route('/', name: 'app_home')]
    public function home(): Response 
    {
        return $this->render('include/_navbar.html.twig',[]);
    }

    public function footer(): Response
    {
        return $this->render('include/_footer.html.twig', []);
    }
}
