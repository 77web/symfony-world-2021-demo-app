<?php
declare(strict_types=1);

namespace App\Domain\Magazine;

interface MagazineReaderInterface
{
    public function getEmail(): ?string;
}
