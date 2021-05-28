<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\MagazineReader;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SendThankYouMail
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * SendThankYouMail constructor.
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendThankYouMail(MagazineReader $readerObj): void
    {
        $message = new \Swift_Message();
        $message
            ->setBody('You have successfully subscribed to our magazine.')
            ->setSubject('Thank you!')
            ->setTo($readerObj->getEmail())
        ;
        if ($this->mailer->send($message) === 0) {
            throw new BadRequestHttpException();
        }
    }
}
