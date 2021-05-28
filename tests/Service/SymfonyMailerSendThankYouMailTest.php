<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Domain\Magazine\SendThankYouMailFailedException;
use App\Entity\MagazineReader;
use App\Service\SymfonyMailerSendThankYouMail;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SymfonyMailerSendThankYouMailTest extends TestCase
{
    /**
     * @var ObjectProphecy|null
     */
    private $mailerP;

    protected function setUp(): void
    {
        $this->mailerP = $this->prophesize(MailerInterface::class);
    }

    protected function tearDown(): void
    {
        $this->mailerP = null;
    }

    public function test()
    {
        $reader = new MagazineReader();
        $reader->setEmail($email = 'hello@localhost.localdomain');

        $this->mailerP->send(Argument::that(function(Email $message) use ($email) {
            return $message->getTo()[0]->getAddress() === $email;
        }))->shouldBeCalled();

        $this->getSUT()->sendThankYouMail($reader);
    }

    public function testThrowExceptionOnFailedToSendThankYouMail()
    {
        $this->expectException(SendThankYouMailFailedException::class);

        $reader = new MagazineReader();
        $reader->setEmail($email = 'hello@localhost.localdomain');

        $this->mailerP->send(Argument::type(Email::class))->willThrow($this->prophesize(TransportExceptionInterface::class)->reveal())->shouldBeCalled();

        $this->getSUT()->sendThankYouMail($reader);
    }

    private function getSUT(): SymfonyMailerSendThankYouMail
    {
        return new SymfonyMailerSendThankYouMail(
            $this->mailerP->reveal()
        );
    }
}
