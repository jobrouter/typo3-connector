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
use Brotkrueml\JobRouterConnector\Domain\Model\Connection;
use Brotkrueml\JobRouterConnector\Domain\Repository\ConnectionRepository;
use Brotkrueml\JobRouterConnector\Extension;
use Brotkrueml\JobRouterConnector\RestClient\RestClientFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 @internal
 */
final class ConnectionAjaxController
{
    private const ERROR_MESSAGE_MAX_LENGTH = 1000;

    /**
     * @var ConnectionRepository
     */
    private $connectionRepository;

    public function __construct()
    {
        $this->connectionRepository = GeneralUtility::makeInstance(ConnectionRepository::class);
    }

    public function checkAction(ServerRequestInterface $request): ResponseInterface
    {
        $connectionId = (int)$request->getParsedBody()['connectionId'];

        $result = [
            'check' => 'ok',
        ];
        try {
            /** @var Connection $connection */
            $connection = $this->connectionRepository->findByIdentifierWithHidden($connectionId);

            if ($connection) {
                (new RestClientFactory())->create($connection, 10);
            } else {
                $result = [
                    'error' => \sprintf(
                        $this->getLanguageService()->sL(Extension::LANGUAGE_PATH_BACKEND_MODULE . ':connection_not_found'),
                        $connectionId
                    ),
                ];
            }
        } catch (HttpException $e) {
            $result = [
                'error' => \sprintf(
                    "%s: %d\n%s",
                    $this->getLanguageService()->sL(Extension::LANGUAGE_PATH_BACKEND_MODULE . ':returned_http_status_code'),
                    $e->getCode(),
                    \substr($e->getMessage(), 0, self::ERROR_MESSAGE_MAX_LENGTH)
                ),
            ];
        } catch (\Exception $e) {
            $result = [
                'error' => \substr($e->getMessage(), 0, self::ERROR_MESSAGE_MAX_LENGTH),
            ];
        }

        return new JsonResponse($result);
    }

    private function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
