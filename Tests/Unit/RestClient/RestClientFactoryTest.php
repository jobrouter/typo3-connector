<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Connector\Tests\Unit\RestClient;

use JobRouter\AddOn\Typo3Connector\Domain\Entity\Connection;
use JobRouter\AddOn\Typo3Connector\Domain\Repository\ConnectionRepository;
use JobRouter\AddOn\Typo3Connector\Exception\CryptException;
use JobRouter\AddOn\Typo3Connector\RestClient\RestClientFactory;
use JobRouter\AddOn\Typo3Connector\Service\Crypt;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

final class RestClientFactoryTest extends TestCase
{
    private ConnectionRepository&Stub $connectionRepositoryStub;
    private Crypt&Stub $cryptServiceStub;
    private RestClientFactory $subject;

    protected function setUp(): void
    {
        $this->connectionRepositoryStub = $this->createStub(ConnectionRepository::class);
        $this->cryptServiceStub = $this->createStub(Crypt::class);
        $this->subject = new RestClientFactory($this->connectionRepositoryStub, $this->cryptServiceStub);
    }

    #[Test]
    public function createThrowsAuthenticationExceptionWhenPasswordCannotBeDecrypted(): void
    {
        $this->expectException(CryptException::class);
        $this->expectExceptionCode(1636467052);
        $this->expectExceptionMessage('The password of the connection with the handle "some handle" cannot be decrypted!');

        $cryptException = new CryptException('some crypt exception');

        $connection = Connection::fromArray([
            'uid' => 1,
            'name' => '',
            'handle' => 'some handle',
            'base_url' => '',
            'username' => '',
            'password' => 'some password',
            'timeout' => 0,
            'verify' => true,
            'proxy' => '',
            'jobrouter_version' => '',
            'disabled' => false,
        ]);

        $this->cryptServiceStub
            ->method('decrypt')
            ->with('some password')
            ->willThrowException($cryptException);

        $this->subject->create($connection);
    }
}
