<?php
declare(strict_types=1);

namespace App\Domain\Magazine;

use Symfony\Component\Validator\Constraints as Assert;

class MagazineReader implements MagazineReaderInterface
{
    /**
     * @var string|null
     * @Assert\Email
     * @Assert\NotBlank
     */
    private $email;

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
}
