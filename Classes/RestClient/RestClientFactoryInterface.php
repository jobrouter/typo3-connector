<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\RestClient;

use Brotkrueml\JobRouterConnector\Domain\Entity\Connection;
use JobRouter\AddOn\RestClient\Client\ClientInterface;

interface RestClientFactoryInterface
{
    /**
     * Creates the Rest client for the given connection
     *
     * @param Connection $connection The connection model
     * @param int|null $lifetime Optional lifetime argument
     * @param string|null $userAgentAddition Addition to the user agent
     */
    public function create(
        Connection $connection,
        ?int $lifetime = null,
        ?string $userAgentAddition = null,
    ): ClientInterface;
}
