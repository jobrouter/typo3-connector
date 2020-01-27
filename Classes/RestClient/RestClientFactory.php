<?php
declare(strict_types=1);

namespace Brotkrueml\JobRouterConnector\RestClient;

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Brotkrueml\JobRouterClient\Client\RestClient;
use Brotkrueml\JobRouterClient\Configuration\ClientConfiguration;
use Brotkrueml\JobRouterClient\Exception\ExceptionInterface;
use Brotkrueml\JobRouterConnector\Domain\Model\Connection;
use Brotkrueml\JobRouterConnector\Service\Crypt;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

final class RestClientFactory
{
    /**
     * @var string
     */
    private static $version;

    /**
     * Creates the Rest client for the given connection
     *
     * @param Connection $connection The connection model
     * @param int|null $lifetime Optional lifetime argument
     * @return RestClient
     * @throws ExceptionInterface
     */
    public function create(Connection $connection, ?int $lifetime = null): RestClient
    {
        $decryptedPassword = (new Crypt())->decrypt($connection->getPassword());

        $configuration = new ClientConfiguration(
            $connection->getBaseUrl(),
            $connection->getUsername(),
            $decryptedPassword
        );
        $configuration = $configuration->withUserAgentAddition($this->getUserAgentAddition());

        if ($lifetime) {
            $configuration = $configuration->withLifetime($lifetime);
        }

        return new RestClient($configuration);
    }

    private function getUserAgentAddition(): string
    {
        if (!static::$version) {
            include ExtensionManagementUtility::extPath('jobrouter_connector') . '/ext_emconf.php';
            static::$version = \array_pop($EM_CONF)['version'];
        }

        return \sprintf('TYPO3Connector/%s', static::$version);
    }
}
