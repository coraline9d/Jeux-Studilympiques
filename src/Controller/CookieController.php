<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CookieController extends AbstractController
{
    public function setCookie()
    {
        $cookie = Cookie::create('accept_cookies', 'true', time() + 86400);
        $response = new Response();
        $response->headers->setCookie($cookie);
    }
}
