<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Domain\Repository;

use Brotkrueml\JobRouterConnector\Domain\Entity\Connection;
use Brotkrueml\JobRouterConnector\Exception\ConnectionNotFoundException;
use TYPO3\CMS\Core\Database\Connection as DatabaseConnection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;

class ConnectionRepository
{
    private const TABLE_NAME = 'tx_jobrouterconnector_domain_model_connection';

    public function __construct(
        private readonly ConnectionPool $connectionPool,
    ) {
    }

    /**
     * @return Connection[]
     */
    public function findAllWithHidden(): array
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::TABLE_NAME);
        $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);

        $result = $queryBuilder
            ->select('*')
            ->from(self::TABLE_NAME)
            ->orderBy('disabled', 'ASC')
            ->addOrderBy('name', 'ASC')
            ->executeQuery();

        $connections = [];
        while ($row = $result->fetchAssociative()) {
            $connections[] = Connection::fromArray($row);
        }

        return $connections;
    }

    public function findByUid(int $uid): Connection
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::TABLE_NAME);

        $row = $queryBuilder
            ->select('*')
            ->from(self::TABLE_NAME)
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, DatabaseConnection::PARAM_INT)),
            )
            ->executeQuery()
            ->fetchAssociative();

        if ($row === false) {
            throw ConnectionNotFoundException::forUid($uid);
        }

        return Connection::fromArray($row);
    }

    public function findByUidWithHidden(int $uid): Connection
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable(self::TABLE_NAME);
        $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);

        $row = $queryBuilder
            ->select('*')
            ->from(self::TABLE_NAME)
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, DatabaseConnection::PARAM_INT)),
            )
            ->executeQuery()
            ->fetchAssociative();

        if ($row === false) {
            throw ConnectionNotFoundException::forUid($uid);
        }

        return Connection::fromArray($row);
    }

    public function updateJobRouterVersion(int $identifier, string $version): int
    {
        return $this->connectionPool
            ->getConnectionForTable(self::TABLE_NAME)
            ->update(
                self::TABLE_NAME,
                [
                    'jobrouter_version' => $version,
                ],
                [
                    'uid' => $identifier,
                ],
            );
    }
}
