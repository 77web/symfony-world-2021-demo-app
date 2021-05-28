<?php

namespace App\Controller;

use App\Domain\Magazine\SendThankYouMailFailedException;
use App\Entity\MagazineReader;
use App\Form\ReaderRegistrationType;
use App\Service\SendThankYouMail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MagazineReaderController extends AbstractController
{
    /**
     * @var SendThankYouMail
     */
    private $sendThankYouMail;

    /**
     * MagazineReaderController constructor.
     * @param SendThankYouMail $sendThankYouMail
     */
    public function __construct(SendThankYouMail $sendThankYouMail)
    {
        $this->sendThankYouMail = $sendThankYouMail;
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

            try {
                $this->sendThankYouMail->sendThankYouMail($readerObj);
            } catch (SendThankYouMailFailedException $e) {
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
