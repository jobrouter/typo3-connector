<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Controller;

use Brotkrueml\JobRouterClient\Exception\HttpException;
use Brotkrueml\JobRouterConnector\Domain\Entity\ConnectionTestResult;
use Brotkrueml\JobRouterConnector\Domain\Model\Connection;
use Brotkrueml\JobRouterConnector\Domain\Repository\ConnectionRepository;
use Brotkrueml\JobRouterConnector\Extension;
use Brotkrueml\JobRouterConnector\RestClient\RestClientFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use TYPO3\CMS\Core\Localization\LanguageService;

/**
 * @internal
 */
final class ConnectionTestController
{
    private const ERROR_MESSAGE_MAX_LENGTH = 1000;

    /**
     * @var ConnectionRepository
     */
    private $connectionRepository;
    /**
     * @var RestClientFactoryInterface
     */
    private $restClientFactory;
    /**
     * @var ResponseFactoryInterface
     */
    private $responseFactory;
    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    public function __construct(
        ConnectionRepository $connectionRepository,
        RestClientFactoryInterface $restClientFactory,
        ResponseFactoryInterface $responseFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->connectionRepository = $connectionRepository;
        $this->restClientFactory = $restClientFactory;
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        if (! \is_array($body)) {
            return $this->buildResponse('Request has no valid body!');
        }

        $connectionId = (int)($body['connectionId'] ?? 0);
        try {
            $connection = $this->connectionRepository->findByIdentifierWithHidden($connectionId);

            if (! $connection instanceof Connection) {
                return $this->buildResponse(\sprintf(
                    $this->getLanguageService()->sL(Extension::LANGUAGE_PATH_BACKEND_MODULE . ':connection_not_found'),
                    $connectionId
                ));
            }

            $this->restClientFactory->create($connection, 10);
            return $this->buildResponse();
        } catch (HttpException $e) {
            return $this->buildResponse(\sprintf(
                "%s: %d\n%s",
                $this->getLanguageService()->sL(Extension::LANGUAGE_PATH_BACKEND_MODULE . ':returned_http_status_code'),
                $e->getCode(),
                \substr($e->getMessage(), 0, self::ERROR_MESSAGE_MAX_LENGTH)
            ));
        } catch (\Throwable $t) {
            return $this->buildResponse(\substr($t->getMessage(), 0, self::ERROR_MESSAGE_MAX_LENGTH));
        }
    }

    private function buildResponse(string $errorMessage = ''): ResponseInterface
    {
        $result = new ConnectionTestResult($errorMessage);

        return $this->responseFactory->createResponse(200)
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withBody($this->streamFactory->createStream($result->toJson()));
    }

    private function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
