<?php

declare(strict_types=1);

namespace App\Purger;

use Doctrine\Common\DataFixtures\Purger\ORMPurgerInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

abstract class AbstractPurger implements ORMPurgerInterface
{
    public const SKIP = [];
    protected EntityManagerInterface $em;

    public function setEntityManager(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $className
     */
    protected function truncate(string $className): void
    {
        /** @var ClassMetadata<T> $cmd */
        $cmd = $this->em->getClassMetadata($className);

        $this->truncateTable($cmd->getTableName());
    }

    protected function truncateTable(string $table): void
    {
        if (in_array($table, self::SKIP)) {
            return;
        }

        /** @var Connection $connection */
        $connection = $this->em->getConnection();

        /** @var AbstractPlatform $dbPlatform */
        $dbPlatform = $connection->getDatabasePlatform();

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS=0');
        /** @var literal-string $q */
        $q = $dbPlatform->getTruncateTableSql($table);
        $connection->executeStatement($q);
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS=1');
    }
}
