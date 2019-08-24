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
}
