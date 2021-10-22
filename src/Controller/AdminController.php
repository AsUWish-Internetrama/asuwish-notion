<?php

namespace App\Controller;

use App\Form\OfferType;
use App\Entity\Offer;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin", name="admin")
     */
    public function index(OfferRepository $offerRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'offers' => $offerRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/create", name="admin_create")
     */
    public function create(Request $request, EntityManagerInterface $em, OfferRepository $offerRepository): Response
    {
        $form = $this->createForm(OfferType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $newOffer = new Offer();

            /** Set title if exist */
            if (array_key_exists('title', $data) && $data['title']) {
                $newOffer->setTitle($data['title']);
            }

            /** Set description if exist */
            if (array_key_exists('description', $data) && $data['description']) {
                $newOffer->setDescription($data['description']);
            }

            /** Upload file HTML Notion */ 
            if ($file = $form->get('file')->getData()) {
                $newFilename = uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('file_directory_offer'),
                        $newFilename
                    );
                    $newOffer->setFile($newFilename);
                } catch (\Exception $e) {}
            }

            /** Save new offer to file */
            $em->persist($newOffer);
            $em->flush();
        }

        return $this->render('admin/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
