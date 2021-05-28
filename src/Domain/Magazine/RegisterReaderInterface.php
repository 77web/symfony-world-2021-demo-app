<?php
declare(strict_types=1);

namespace App\Domain\Magazine;

interface RegisterReaderInterface
{
    public function registerReader(MagazineReaderInterface $readerObj): void;
}
