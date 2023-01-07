<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\RestClient;

use Brotkrueml\JobRouterClient\Client\ClientInterface;
use Brotkrueml\JobRouterClient\Client\RestClient;
use Brotkrueml\JobRouterClient\Configuration\ClientConfiguration;
use Brotkrueml\JobRouterClient\Configuration\ClientOptions;
use Brotkrueml\JobRouterClient\Exception\ExceptionInterface;
use Brotkrueml\JobRouterConnector\Domain\Entity\Connection;
use Brotkrueml\JobRouterConnector\Domain\Repository\ConnectionRepository;
use Brotkrueml\JobRouterConnector\Exception\CryptException;
use Brotkrueml\JobRouterConnector\Extension;
use Brotkrueml\JobRouterConnector\Service\Crypt;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

final class RestClientFactory implements RestClientFactoryInterface
{
    private string $extensionVersion = '';

    public function __construct(
        private readonly ConnectionRepository $connectionRepository,
        private readonly Crypt $cryptService,
    ) {
    }

    /**
     * Creates the Rest client for the given connection
     *
     * @param Connection $connection The connection model
     * @param int|null $lifetime Optional lifetime argument
     * @param string|null $userAgentAddition Addition to the user agent
     * @throws CryptException
     * @throws ExceptionInterface
     */
    public function create(
        Connection $connection,
        ?int $lifetime = null,
        ?string $userAgentAddition = null,
    ): ClientInterface {
        try {
            $decryptedPassword = $this->cryptService->decrypt($connection->encryptedPassword);
        } catch (CryptException $e) {
            throw new CryptException(
                \sprintf(
                    'The password of the connection with the handle "%s" cannot be decrypted!',
                    $connection->handle,
                ),
                1636467052,
                $e,
            );
        }

        $configuration = new ClientConfiguration(
            $connection->baseUrl,
            $connection->username,
            $decryptedPassword,
        );

        $configuration = $configuration
            ->withUserAgentAddition($userAgentAddition ?? $this->getUserAgentAddition())
            ->withClientOptions(
                new ClientOptions(false, 5, $connection->timeout, $connection->verify, $connection->proxy),
            );
        if ($lifetime) {
            $configuration = $configuration->withLifetime($lifetime);
        }

        $client = (new RestClient($configuration))->authenticate();

        $this->updateJobRouterVersion($client, $connection);

        return $client;
    }

    private function getUserAgentAddition(): string
    {
        if ($this->extensionVersion === '') {
            $this->extensionVersion = ExtensionManagementUtility::getExtensionVersion(Extension::KEY);
        }

        return \sprintf(
            'TYPO3-JobRouter-Connector/%s (https://extensions.typo3.org/extension/jobrouter_connector)',
            $this->extensionVersion,
        );
    }

    private function updateJobRouterVersion(ClientInterface $client, Connection $connection): void
    {
        if ($client->getJobRouterVersion() === $connection->jobrouterVersion) {
            return;
        }

        $this->connectionRepository->updateJobRouterVersion($connection->uid, $client->getJobRouterVersion());
    }
}
