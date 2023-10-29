<?php

declare(strict_types=1);

namespace App\Domain\Repository;

interface ConnectionInterface
{
    public function beginTransaction();

    public function commit();

    public function rollback();
}
