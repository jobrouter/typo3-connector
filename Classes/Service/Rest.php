<?php
declare(strict_types = 1);

namespace Brotkrueml\JobRouterConnector\Service;

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Brotkrueml\JobRouterClient\Client\RestClient;
use Brotkrueml\JobRouterClient\Configuration\ClientConfiguration;
use Brotkrueml\JobRouterClient\Exception\RestException;
use Brotkrueml\JobRouterConnector\Domain\Model\Connection;

class Rest
{
    /**
     * Get the Rest client for the given connection
     *
     * @param Connection $connection The connection model
     * @param int|null $lifetime Optional lifetime argument
     * @return RestClient
     */
    public function getRestClient(Connection $connection, ?int $lifetime = null): RestClient
    {
        $decryptedPassword = (new Crypt())->decrypt($connection->getPassword());

        $configuration = new ClientConfiguration(
            $connection->getBaseUrl(),
            $connection->getUsername(),
            $decryptedPassword
        );

        if ($lifetime) {
            $configuration->setLifetime($lifetime);
        }

        return new RestClient($configuration);
    }

    /**
     * Check the connectivity for the given connection
     *
     * @param Connection $connection The connection model
     * @return string
     */
    public function checkConnection(Connection $connection): string
    {
        try {
            $this->getRestClient($connection, 10);
        } catch (RestException $e) {
            if (method_exists($e->getPrevious(), 'getResponse')) {
                return $this->getReadableErrorMessage($e->getPrevious()->getResponse()->getContent(false));
            }

            return $e->getMessage();
        }

        return '';
    }

    /**
     * Get the readable error message from a JSON string
     *
     * @param string $errorMessageAsJsonString JobRouter error message as JSON string
     * @return string
     */
    public function getReadableErrorMessage(string $errorMessageAsJsonString): string
    {
        $errorMessage = \json_decode($errorMessageAsJsonString, true);

        if ($errorMessage && isset($errorMessage['errors']['-']) && \is_array($errorMessage['errors']['-'])) {
            return implode(' / ', $errorMessage['errors']['-']);
        }

        return $errorMessageAsJsonString;
    }
}
