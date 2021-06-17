<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Domain\Magazine\MagazineReaderInterface;
use App\Service\DoctrineDBALRegisterReader;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

class DoctrineDBALRegisterReaderTest extends TestCase
{
    public function test()
    {
        $connectionP = $this->prophesize(Connection::class);
        $readerObjP = $this->prophesize(MagazineReaderInterface::class);
        $readerObjP->getEmail()->willReturn('hello@localhost');

        $connectionP->insert('magazine_reader', ['email' => 'hello@localhost'])->shouldBeCalled();

        $SUT = new DoctrineDBALRegisterReader(
            $connectionP->reveal()
        );
        $SUT->registerReader($readerObjP->reveal());
    }
}
