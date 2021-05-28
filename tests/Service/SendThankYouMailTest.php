<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\MagazineReader;
use App\Service\SendThankYouMail;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SendThankYouMailTest extends TestCase
{
    /**
     * @var ObjectProphecy|null
     */
    private $mailerP;

    protected function setUp(): void
    {
        $this->mailerP = $this->prophesize(\Swift_Mailer::class);
    }

    protected function tearDown(): void
    {
        $this->mailerP = null;
    }

    public function test()
    {
        $reader = new MagazineReader();
        $reader->setEmail($email = 'hello@localhost.localdomain');

        $this->mailerP->send(Argument::that(function(\Swift_Message $message) use ($email) {
            return array_keys($message->getTo()) === [$email];
        }))->willReturn(1)->shouldBeCalled();

        $this->getSUT()->sendThankYouMail($reader);
    }

    public function testThrowExceptionOnFailedToSendThankYouMail()
    {
        $this->expectException(BadRequestHttpException::class);

        $reader = new MagazineReader();
        $reader->setEmail($email = 'hello@localhost.localdomain');

        $this->mailerP->send(Argument::type(\Swift_Message::class))->willReturn(0)->shouldBeCalled();

        $this->getSUT()->sendThankYouMail($reader);
    }

    private function getSUT(): SendThankYouMail
    {
        return new SendThankYouMail(
            $this->mailerP->reveal()
        );
    }
}
