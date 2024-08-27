<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Connector\Controller;

use JobRouter\AddOn\RestClient\Exception\HttpException;
use JobRouter\AddOn\Typo3Connector\Domain\Dto\ConnectionTestResult;
use JobRouter\AddOn\Typo3Connector\Domain\Repository\ConnectionRepository;
use JobRouter\AddOn\Typo3Connector\Exception\ConnectionNotFoundException;
use JobRouter\AddOn\Typo3Connector\Extension;
use JobRouter\AddOn\Typo3Connector\RestClient\RestClientFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Core\Localization\LanguageService;

/**
 * @internal
 */
#[AsController]
final class ConnectionTestController
{
    private const ERROR_MESSAGE_MAX_LENGTH = 1000;

    public function __construct(
        private readonly ConnectionRepository $connectionRepository,
        private readonly RestClientFactoryInterface $restClientFactory,
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly StreamFactoryInterface $streamFactory,
    ) {}

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        if (! \is_array($body)) {
            return $this->buildResponse('Request has no valid body!');
        }

        $connectionId = (int) ($body['connectionId'] ?? 0);
        try {
            $connection = $this->connectionRepository->findByUid($connectionId, true);
        } catch (ConnectionNotFoundException) {
            return $this->buildResponse(\sprintf(
                $this->getLanguageService()->sL(Extension::LANGUAGE_PATH_BACKEND_MODULE . ':connection_not_found'),
                $connectionId,
            ));
        }

        try {
            $this->restClientFactory->create($connection, 10);
            return $this->buildResponse();
        } catch (HttpException $e) {
            return $this->buildResponse(\sprintf(
                "%s: %d\n%s",
                $this->getLanguageService()->sL(Extension::LANGUAGE_PATH_BACKEND_MODULE . ':returned_http_status_code'),
                $e->getCode(),
                \substr($e->getMessage(), 0, self::ERROR_MESSAGE_MAX_LENGTH),
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
