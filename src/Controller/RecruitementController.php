<?php

namespace App\Controller;

use App\Service\BlockHelper;
use App\Service\ApiMailjet;
use App\Form\RecruitmentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RecruitementController extends AbstractController
{
    /**
     * Construct function
     * 
     * @param ApiMailjet $apiMailjet
     * @param BlockHelper $blockHelper
     */
    public function __construct(
        BlockHelper $blockHelper,
        ApiMailjet $apiMailjet
    ) {
        $this->_blockHelper = $blockHelper;
        $this->_apiMailjet = $apiMailjet;
    }

    /**
     * @Route("/recruitement", name="recruitement")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(RecruitmentType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $candidateData = $form->getData();

            /** Verify if data exists */
            if (is_array($candidateData) && count($candidateData) >= 1) {
                $this->_blockHelper->setUrl('/pages');

                /** Set status */
                $this->_blockHelper->setProperty([
                    'type' => 'Select',
                    'title' => 'Etape',
                    'data' => 'Candidature'
                ]);

                /** Set block title */
                if ($candidateData['firstName'] && $candidateData['lastName']) {
                    $this->_blockHelper->setProperty([
                        'type' => 'Text',
                        'title' => 'Name',
                        'data' => $candidateData['firstName'] . ' ' . strtoupper($candidateData['lastName'])
                    ]);
                }

                /** Set phone number */
                if ($candidateData['phoneNumber']) {
                    $this->_blockHelper->setProperty([
                        'type' => 'RichText',
                        'title' => 'T??l??phone',
                        'data' => $candidateData['phoneNumber']
                    ]);
                }

                /** Set candidate type */
                if ($candidateData['candidateType']) {
                    $this->_blockHelper->setProperty([
                        'type' => 'Select',
                        'title' => 'Type de candidature',
                        'data' => $candidateData['candidateType']
                    ]);
                }

                /** Set email */
                if ($candidateData['email']) {
                    $this->_blockHelper->setProperty([
                        'type' => 'Email',
                        'title' => 'Email',
                        'data' => $candidateData['email']
                    ]);
                }

                /** Set actual status */ 
                if ($candidateData['actualStatus']) {
                    $this->_blockHelper->setProperty([
                        'type' => 'Select',
                        'title' => 'Statut actuel',
                        'data' => $candidateData['actualStatus']
                    ]);
                }

                /** Set location */ 
                if ($candidateData['location']) {
                    $this->_blockHelper->setProperty([
                        'type' => 'Select',
                        'title' => 'Location',
                        'data' => $candidateData['location']
                    ]);
                }

                /** Set agency */
                if ($candidateData['agencies']) {
                    $this->_blockHelper->setProperty([
                        'type' => 'Select',
                        'title' => 'Agence souhait??e',
                        'data' => $candidateData['agencies']
                    ]);
                }

                /** Set website */
                if ($candidateData['website']) {
                    $this->_blockHelper->setProperty([
                        'type' => 'Url',
                        'title' => 'Site',
                        'data' => $candidateData['website']
                    ]);
                }

                /** Set linkedin */
                if ($candidateData['linkedin']) {
                    $this->_blockHelper->setProperty([
                        'type' => 'Url',
                        'title' => 'Profil Linkedin',
                        'data' => $candidateData['linkedin']
                    ]);
                }

                /** Save file and send link */
                if ($file = $form->get('file')->getData()) {
                    $newFilename = uniqid().'.'.$file->guessExtension();
    
                    try {
                        $file->move(
                            $this->getParameter('file_directory_cv'),
                            $newFilename
                        );

                        $this->_blockHelper->setProperty([
                            'type' => 'Url',
                            'title' => 'CV',
                            'data' => 'https://asuwish-notion.site-client.com/uploads/cv/'.$newFilename
                        ]);
                    } catch (\Exception $e) {}
                }

                /** Set children */
                if ($candidateData['description']) {
                    $this->_blockHelper->setChildren([
                        'title' => 'Parlez nous un peu de vous :',
                        'data' => $candidateData['description']
                    ]);
                }

                $this->_blockHelper->sendRequest();
                $this->_blockHelper->resetData();

                /** Send email confirmation */
                if ($candidateData['email']) {
                    try {
                        $this->_apiMailjet->sendEmail($candidateData['email']);
                    } catch (\Exception $e) {}
                }

                $this->addFlash('success', 'Votre demande a ??t?? transmise, vous aurez une r??ponse sous peu.');
            } else {
                $this->addFlash('warning', 'Votre demande n\'a pas ??t?? transmise, un probl??me est survenue.');
            }
            return $this->redirectToRoute('recruitement');
        }

        return $this->render('recruitement/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}