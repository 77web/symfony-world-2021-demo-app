<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Domain\Magazine\MagazineReaderInterface;
use App\Service\DoctrineORMRegisterReader;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class DoctrineORMRegisterReaderTest extends TestCase
{
    public function test()
    {
        $emP = $this->prophesize(EntityManagerInterface::class);
        $readerObj = $this->prophesize(MagazineReaderInterface::class)->reveal();

        $emP->persist($readerObj)->shouldBeCalled();
        $emP->flush()->shouldBeCalled();

        $SUT = new DoctrineORMRegisterReader($emP->reveal());
        $SUT->registerReader($readerObj);
    }
}
