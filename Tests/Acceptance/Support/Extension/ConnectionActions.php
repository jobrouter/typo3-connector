<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Tests\Acceptance\Support\Extension;

use Brotkrueml\JobRouterConnector\Exception\KeyFileException;
use Brotkrueml\JobRouterConnector\Service\Crypt;
use Brotkrueml\JobRouterConnector\Service\KeyGenerator;
use Brotkrueml\JobRouterConnector\Utility\FileUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Testbase;

trait ConnectionActions
{
    public function createJobRouterKey(): void
    {
        $fileUtility = new FileUtility();
        (new KeyGenerator(new Crypt($fileUtility), $fileUtility))->generateAndStoreKey();
    }

    public function deleteJobRouterKey(): void
    {
        try {
            \unlink((new FileUtility())->getAbsoluteKeyPath());
        } catch (KeyFileException $e) {
            // do nothing
        }
    }

    public function encryptPassword(string $unencryptedPassword): string
    {
        $crypt = new Crypt(new FileUtility());

        return $crypt->encrypt($unencryptedPassword);
    }

    public function truncateConnectionTable(): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionByName(ConnectionPool::DEFAULT_CONNECTION_NAME);
        $connection->truncate('tx_jobrouterconnector_domain_model_connection');
    }

    public function importDatabaseFixture(): void
    {
        (new TestBase())->importXmlDatabaseFixture('EXT:jobrouter_connector/Tests/Acceptance/Fixtures/tx_jobrouterconnector_domain_model_connection.xml');
    }
}
