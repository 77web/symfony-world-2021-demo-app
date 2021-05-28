<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\Magazine\SendThankYouMailFailedException;
use App\Domain\Magazine\SendThankYouMailInterface;
use App\Entity\MagazineReader;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SymfonyMailerSendThankYouMail implements SendThankYouMailInterface
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * SymfonyMailerSendThankYouMail constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendThankYouMail(MagazineReader $readerObj): void
    {
        $message = new Email();
        $message
            ->from('office@localhost.localdomain')
            ->to($readerObj->getEmail())
            ->subject('Thank you!')
            ->text('You have successfully subscribed to our magazine.')
        ;

        try {
            $this->mailer->send($message);
        } catch (TransportExceptionInterface $e) {
            throw new SendThankYouMailFailedException();
        }
    }
}
