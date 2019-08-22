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

class Rest
{
    public function getRestClient(
        string $baseUrl,
        string $username,
        string $password,
        ?int $lifetime = null
    ): RestClient {
        $configuration = new ClientConfiguration($baseUrl, $username, $password);

        if ($lifetime) {
            $configuration->setLifetime($lifetime);
        }

        return new RestClient($configuration);
    }

    public function checkConnection(string $baseUrl, string $username, string $password): string
    {
        try {
            $this->getRestClient($baseUrl, $username, $password, 10);
        } catch (RestException $e) {
            if (method_exists($e->getPrevious(), 'getResponse')) {
                $result = \json_decode($e->getPrevious()->getResponse()->getContent(false), true);

                if ($result && isset($result['errors']['-']) && \is_array($result['errors']['-'])) {
                    return implode(' / ', $result['errors']['-']);
                }
            }

            return $e->getMessage();
        }

        return '';
    }
}
