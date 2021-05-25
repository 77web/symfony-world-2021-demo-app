<?php

namespace App\Controller;

use App\Form\ReaderRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MagazineReaderController extends AbstractController
{
    /**
     * @Route("/", name="magazine_reader")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(ReaderRegistrationType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Successfully registered.');

            return $this->redirectToRoute('magazine_reader');
        }

        return $this->render('magazine_reader/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
