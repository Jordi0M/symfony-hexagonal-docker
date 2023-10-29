<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Repository\ConnectionInterface;
use Doctrine\DBAL\Connection;

abstract class AbstractRepository implements ConnectionInterface
{
    protected Connection $connection;


    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    /**
     * @throws
     */
    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }


    /**
     * @throws
     */
    public function commit(): void
    {
        $this->connection->commit();
    }


    /**
     * @throws
     */
    public function rollback(): void
    {
        $this->connection->rollBack();
    }
}
