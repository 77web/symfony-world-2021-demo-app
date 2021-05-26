<?php

namespace App\Controller;

use App\Entity\MagazineReader;
use App\Form\ReaderRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MagazineReaderController extends AbstractController
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * MagazineReaderController constructor.
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/", name="magazine_reader")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(ReaderRegistrationType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var MagazineReader $readerObj */
            $readerObj = $form->getData();

            // Save reader obj.
            $em = $this->getDoctrine()->getManager();
            $em->persist($readerObj);
            $em->flush();

            // Send thank you mail.
            $message = new \Swift_Message();
            $message
                ->setBody('You have successfully subscribed to our magazine.')
                ->setSubject('Thank you!')
                ->setTo($readerObj->getEmail())
            ;
            if ($this->mailer->send($message) === 0) {
                throw new BadRequestHttpException();
            }

            $this->get('session')->getFlashBag()->add('success', 'Successfully registered.');

            return $this->redirectToRoute('magazine_reader');
        }

        return $this->render('magazine_reader/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
