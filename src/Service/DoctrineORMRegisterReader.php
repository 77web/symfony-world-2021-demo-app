<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\Magazine\MagazineReaderInterface;
use App\Domain\Magazine\RegisterReaderInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineORMRegisterReader implements RegisterReaderInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * DoctrineORMRegisterReader constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function registerReader(MagazineReaderInterface $readerObj): void
    {
        $this->em->persist($readerObj);
        $this->em->flush();
    }
}
