<?php

namespace App\Controller;

use App\Entity\Offer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    /**
     * @Route("/offer/{id}", name="offer_show")
     */
    public function index(Offer $offer): Response
    {
        return $this->render('offer/index.html.twig', [
            'offer' => $offer,
        ]);
    }
}
