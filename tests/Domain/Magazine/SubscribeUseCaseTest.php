<?php
declare(strict_types=1);

namespace App\Tests\Domain\Magazine;

use App\Domain\Magazine\MagazineReaderInterface;
use App\Domain\Magazine\RegisterReaderInterface;
use App\Domain\Magazine\SendThankYouMailFailedException;
use App\Domain\Magazine\SendThankYouMailInterface;
use App\Domain\Magazine\SubscribeToMagazineFailedException;
use App\Domain\Magazine\SubscribeUseCase;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

class SubscribeUseCaseTest extends TestCase
{
    /**
     * @var ObjectProphecy|null
     */
    private $registerReaderP;

    /**
     * @var ObjectProphecy|null
     */
    private $sendThankYouMailP;

    protected function setUp(): void
    {
        $this->registerReaderP = $this->prophesize(RegisterReaderInterface::class);
        $this->sendThankYouMailP = $this->prophesize(SendThankYouMailInterface::class);
    }

    protected function tearDown(): void
    {
        $this->registerReaderP = null;
        $this->sendThankYouMailP = null;
    }

    public function test()
    {
        $readerObj = $this->prophesize(MagazineReaderInterface::class)->reveal();

        $this->registerReaderP->registerReader($readerObj)->shouldBeCalled();
        $this->sendThankYouMailP->sendThankYouMail($readerObj)->shouldBeCalled();

        $this->getSUT()->subscribeToMagazine($readerObj);
    }

    public function testThrowExceptionOnSendThankYouMailFailed()
    {
        $this->expectException(SubscribeToMagazineFailedException::class);

        $readerObj = $this->prophesize(MagazineReaderInterface::class)->reveal();

        $this->registerReaderP->registerReader($readerObj)->shouldBeCalled();
        $this->sendThankYouMailP->sendThankYouMail($readerObj)->willThrow(new SendThankYouMailFailedException())->shouldBeCalled();

        $this->getSUT()->subscribeToMagazine($readerObj);
    }

    private function getSUT(): SubscribeUseCase
    {
        return new SubscribeUseCase(
            $this->registerReaderP->reveal(),
            $this->sendThankYouMailP->reveal()
        );
    }
}
