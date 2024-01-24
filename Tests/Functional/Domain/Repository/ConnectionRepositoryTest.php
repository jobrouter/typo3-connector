<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Connector\Tests\Functional\Domain\Repository;

use JobRouter\AddOn\Typo3Connector\Domain\Repository\ConnectionRepository;
use JobRouter\AddOn\Typo3Connector\Exception\ConnectionNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Database\Connection as DatabaseConnection;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

final class ConnectionRepositoryTest extends FunctionalTestCase
{
    /**
     * @var string[]
     */
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/jobrouter_connector',
    ];

    private ConnectionRepository $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new ConnectionRepository($this->getConnectionPool());
    }

    #[Test]
    public function findAllWhenNoRecordsAvailable(): void
    {
        $actual = $this->subject->findAll();

        self::assertSame([], $actual);
    }

    #[Test]
    public function findAll(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/tx_jobrouterconnector_domain_model_connection.csv');

        $actual = $this->subject->findAll();

        self::assertCount(1, $actual);

        self::assertSame(1, $actual[0]->uid);
    }

    #[Test]
    public function findAllWithHidden(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/tx_jobrouterconnector_domain_model_connection.csv');

        $actual = $this->subject->findAll(true);

        self::assertCount(2, $actual);

        self::assertSame(1, $actual[0]->uid);
        self::assertSame(2, $actual[1]->uid);
    }

    #[Test]
    public function findByUidReturnsRecord(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/tx_jobrouterconnector_domain_model_connection.csv');

        $actual = $this->subject->findByUid(1);

        self::assertSame(1, $actual->uid);
    }

    #[Test]
    public function findByUidWithDisabledRecordThrowsException(): void
    {
        $this->expectException(ConnectionNotFoundException::class);

        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/tx_jobrouterconnector_domain_model_connection.csv');

        $this->subject->findByUid(2);
    }

    #[Test]
    public function findByUidWithHiddenReturnsDisabledRecord(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/tx_jobrouterconnector_domain_model_connection.csv');

        $actual = $this->subject->findByUid(2, true);

        self::assertSame(2, $actual->uid);
    }

    #[Test]
    public function findByHandleReturnsRecord(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/tx_jobrouterconnector_domain_model_connection.csv');

        $actual = $this->subject->findByHandle('some_handle');

        self::assertSame(1, $actual->uid);
    }

    #[Test]
    public function findByUidWithNotExistingHandleThrowsException(): void
    {
        $this->expectException(ConnectionNotFoundException::class);

        $this->subject->findByHandle('nonexisting');
    }

    #[Test]
    public function updateJobRouterVersion(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/tx_jobrouterconnector_domain_model_connection.csv');

        $actual = $this->subject->updateJobRouterVersion(1, '2023.1.0');

        self::assertSame(1, $actual);

        $updatedVersion = $this->getConnectionPool()
            ->getConnectionForTable('tx_jobrouterconnector_domain_model_connection')
            ->select(
                ['jobrouter_version'],
                'tx_jobrouterconnector_domain_model_connection',
                [
                    'uid' => 1,
                ],
            )
            ->fetchOne();

        self::assertSame('2023.1.0', $updatedVersion);
    }

    #[Test]
    public function updateJobRouterVersionForDisabledRecord(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/tx_jobrouterconnector_domain_model_connection.csv');

        $actual = $this->subject->updateJobRouterVersion(2, '2022.4.0');

        self::assertSame(1, $actual);

        $queryBuilder = $this->getConnectionPool()->getQueryBuilderForTable('tx_jobrouterconnector_domain_model_connection');
        $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);
        $updatedVersion = $queryBuilder
            ->select('jobrouter_version')
            ->from('tx_jobrouterconnector_domain_model_connection')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter(2, DatabaseConnection::PARAM_INT)),
            )
            ->executeQuery()
            ->fetchOne();

        self::assertSame('2022.4.0', $updatedVersion);
    }
}
