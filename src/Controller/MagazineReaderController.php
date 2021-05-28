<?php

namespace App\Controller;

use App\Domain\Magazine\SubscribeToMagazineFailedException;
use App\Domain\Magazine\SubscribeUseCase;
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
     * @var SubscribeUseCase
     */
    private $subscribeUseCase;

    /**
     * MagazineReaderController constructor.
     * @param SubscribeUseCase $subscribeUseCase
     */
    public function __construct(SubscribeUseCase $subscribeUseCase)
    {
        $this->subscribeUseCase = $subscribeUseCase;
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

           try {
               $this->subscribeUseCase->subscribeToMagazine($readerObj);
           } catch (SubscribeToMagazineFailedException $e) {
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
