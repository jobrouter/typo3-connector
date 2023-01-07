<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Tests\Unit\Controller;

use Brotkrueml\JobRouterClient\Exception\HttpException;
use Brotkrueml\JobRouterConnector\Controller\ConnectionTestController;
use Brotkrueml\JobRouterConnector\Domain\Entity\Connection;
use Brotkrueml\JobRouterConnector\Domain\Repository\ConnectionRepository;
use Brotkrueml\JobRouterConnector\Exception\ConnectionNotFoundException;
use Brotkrueml\JobRouterConnector\RestClient\RestClientFactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\ResponseFactory;
use TYPO3\CMS\Core\Http\StreamFactory;
use TYPO3\CMS\Core\Localization\LanguageService;

/**
 * @runTestsInSeparateProcesses
 */
final class ConnectionTestControllerTest extends TestCase
{
    private ConnectionRepository&Stub $connectionRepositoryStub;
    private RestClientFactoryInterface&MockObject $restClientFactoryMock;
    private ServerRequestInterface&Stub $requestStub;
    private ConnectionTestController $subject;

    protected function setUp(): void
    {
        $this->connectionRepositoryStub = $this->createStub(ConnectionRepository::class);
        $this->restClientFactoryMock = $this->createMock(RestClientFactoryInterface::class);

        $this->subject = new ConnectionTestController(
            $this->connectionRepositoryStub,
            $this->restClientFactoryMock,
            new ResponseFactory(),
            new StreamFactory(),
        );

        $this->requestStub = $this->createStub(ServerRequestInterface::class);

        $languageServiceStub = $this->createStub(LanguageService::class);
        $languageServiceStub
            ->method('sL')
            ->willReturnCallback(static fn (string $key): string => $key);

        $GLOBALS['LANG'] = $languageServiceStub;
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['LANG']);
    }

    /**
     * @test
     */
    public function invokeReturnsResponseWithErrorWhenRequestHasInvalidBody(): void
    {
        $this->requestStub
            ->method('getParsedBody')
            ->willReturn(null);

        $actual = $this->subject->__invoke($this->requestStub);
        $actual->getBody()->rewind();

        self::assertJsonStringEqualsJsonString(
            '{"error": "Request has no valid body!"}',
            $actual->getBody()->getContents(),
        );
    }

    /**
     * @test
     */
    public function invokeReturnsResponseWithErrorWhenIdentifierCannotBeFoundInRepository(): void
    {
        $this->requestStub
            ->method('getParsedBody')
            ->willReturn([
                'connectionId' => '42',
            ]);
        $this->connectionRepositoryStub
            ->method('findByUidWithHidden')
            ->with(42)
            ->willThrowException(new ConnectionNotFoundException());

        $actual = $this->subject->__invoke($this->requestStub);
        $actual->getBody()->rewind();

        self::assertJsonStringEqualsJsonString(
            '{"error": "LLL:EXT:jobrouter_connector\/Resources\/Private\/Language\/BackendModule.xlf:connection_not_found"}',
            $actual->getBody()->getContents(),
        );
    }

    /**
     * @test
     */
    public function invokeReturnSuccessfulResponse(): void
    {
        $this->requestStub
            ->method('getParsedBody')
            ->willReturn([
                'connectionId' => '42',
            ]);

        $connection = $this->getConnectionEntity();
        $this->connectionRepositoryStub
            ->method('findByUidWithHidden')
            ->with(42)
            ->willReturn($connection);

        $this->restClientFactoryMock
            ->expects(self::once())
            ->method('create')
            ->with($connection, 10);

        $actual = $this->subject->__invoke($this->requestStub);
        $actual->getBody()->rewind();

        self::assertJsonStringEqualsJsonString(
            '{"check": "ok"}',
            $actual->getBody()->getContents(),
        );
    }

    /**
     * @test
     */
    public function invokeReturnsResponseWithErrorWhenHttpExceptionIsThrown(): void
    {
        $this->requestStub
            ->method('getParsedBody')
            ->willReturn([
                'connectionId' => '42',
            ]);

        $connection = $this->getConnectionEntity();
        $this->connectionRepositoryStub
            ->method('findByUidWithHidden')
            ->with(42)
            ->willReturn($connection);

        $this->restClientFactoryMock
            ->expects(self::once())
            ->method('create')
            ->with($connection, 10)
            ->willThrowException(new HttpException('some message', 500));

        $actual = $this->subject->__invoke($this->requestStub);
        $actual->getBody()->rewind();

        self::assertJsonStringEqualsJsonString(
            '{"error": "LLL:EXT:jobrouter_connector\/Resources\/Private\/Language\/BackendModule.xlf:returned_http_status_code: 500\nsome message"}',
            $actual->getBody()->getContents(),
        );
    }

    /**
     * @test
     */
    public function invokeReturnsResponseWithErrorWhenAnExceptionOtherThanHttpExceptionIsThrown(): void
    {
        $this->requestStub
            ->method('getParsedBody')
            ->willReturn([
                'connectionId' => '42',
            ]);

        $connection = $this->getConnectionEntity();
        $this->connectionRepositoryStub
            ->method('findByUidWithHidden')
            ->with(42)
            ->willReturn($connection);

        $this->restClientFactoryMock
            ->expects(self::once())
            ->method('create')
            ->with($connection, 10)
            ->willThrowException(new \Exception('some message'));

        $actual = $this->subject->__invoke($this->requestStub);
        $actual->getBody()->rewind();

        self::assertJsonStringEqualsJsonString(
            '{"error": "some message"}',
            $actual->getBody()->getContents(),
        );
    }

    /**
     * @test
     */
    public function invokeReturnsResponseWithTruncatedErrorWhenMaximumLengthIsExceeded(): void
    {
        $this->requestStub
            ->method('getParsedBody')
            ->willReturn([
                'connectionId' => '42',
            ]);

        $connection = $this->getConnectionEntity();
        $this->connectionRepositoryStub
            ->method('findByUidWithHidden')
            ->with(42)
            ->willReturn($connection);

        $this->restClientFactoryMock
            ->expects(self::once())
            ->method('create')
            ->with($connection, 10)
            ->willThrowException(new \Exception(\str_pad('', 2000, 'a')));

        $actual = $this->subject->__invoke($this->requestStub);
        $actual->getBody()->rewind();
        $contents = \json_decode($actual->getBody()->getContents(), true, 512, \JSON_THROW_ON_ERROR);

        self::assertSame(1000, \strlen((string)$contents['error']));
    }

    private function getConnectionEntity(): Connection
    {
        return Connection::fromArray([
            'uid' => 42,
            'name' => 'some name',
            'handle' => 'some_handle',
            'base_url' => '',
            'username' => '',
            'password' => '',
            'timeout' => 0,
            'verify' => true,
            'proxy' => '',
            'jobrouter_version' => '',
            'disabled' => false,
        ]);
    }
}
