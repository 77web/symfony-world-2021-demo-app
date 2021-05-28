<?php
declare(strict_types=1);

namespace App\Domain\Magazine;

interface SendThankYouMailInterface
{
    /**
     * @param MagazineReaderInterface $readerObj
     * @throws SendThankYouMailFailedException
     */
    public function sendThankYouMail(MagazineReaderInterface $readerObj): void;
}
