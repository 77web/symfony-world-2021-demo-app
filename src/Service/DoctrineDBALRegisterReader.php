<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\Magazine\MagazineReaderInterface;
use App\Domain\Magazine\RegisterReaderInterface;
use Doctrine\DBAL\Connection;

class DoctrineDBALRegisterReader implements RegisterReaderInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * DoctrineDBALRegisterReader constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function registerReader(MagazineReaderInterface $readerObj): void
    {
        $this->connection->insert('magazine_reader', [
            'email' => $readerObj->getEmail(),
        ]);
    }
}
