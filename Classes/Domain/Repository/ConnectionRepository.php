<?php
declare(strict_types = 1);

namespace Brotkrueml\JobRouterConnector\Domain\Repository;

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for connections
 */
class ConnectionRepository extends Repository
{
    protected $defaultOrderings = [
        'disabled' => QueryInterface::ORDER_ASCENDING,
        'name' => QueryInterface::ORDER_ASCENDING,
    ];

    public function findAllWithHidden()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);

        return $query->execute();
    }

    public function findByIdentifierWithHidden($identifier)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields(true);
        $query->matching($query->equals('uid', $identifier));

        return $query->execute()->getFirst();
    }
}
