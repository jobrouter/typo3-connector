<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Tests\Acceptance\Support\Extension;

use Brotkrueml\JobRouterConnector\Exception\KeyFileException;
use Brotkrueml\JobRouterConnector\Service\Crypt;
use Brotkrueml\JobRouterConnector\Service\KeyGenerator;
use Brotkrueml\JobRouterConnector\Utility\FileUtility;
use Psr\Http\Client\ClientInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Testbase;

trait ConnectionActions
{
    public function createJobRouterKey(): void
    {
        $fileUtility = new FileUtility();
        (new KeyGenerator(new Crypt($fileUtility), $fileUtility))->generateAndStoreKey();
    }

    public function deleteJobRouterKey(): void
    {
        try {
            \unlink((new FileUtility())->getAbsoluteKeyPath());
        } catch (KeyFileException $e) {
            // do nothing
        }
    }

    public function encryptPassword(string $unencryptedPassword): string
    {
        $crypt = new Crypt(new FileUtility());

        return $crypt->encrypt($unencryptedPassword);
    }

    public function truncateConnectionTable(): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionByName(ConnectionPool::DEFAULT_CONNECTION_NAME);
        $connection->truncate('tx_jobrouterconnector_domain_model_connection');
    }

    public function importConnectionFixture(string $baseUrl, string $password): void
    {
        $fixture = <<<EOT
<?xml version="1.0" encoding="utf-8"?>
<dataset>
	<tx_jobrouterconnector_domain_model_connection>
		<uid>1</uid>
		<pid>0</pid>
        <crdate>1603481421</crdate>
		<tstamp>1603481421</tstamp>
        <cruser_id>1</cruser_id>
        <name>Some JobRouter Connection Name</name>
        <handle>some_connection_handle</handle>
        <base_url>$baseUrl</base_url>
        <username>john.doe</username>
        <password>$password</password>
        <jobrouter_version></jobrouter_version>
        <description>Some description</description>
	</tx_jobrouterconnector_domain_model_connection>
</dataset>
EOT;

        $fixturePath = \sprintf('%s/tx_jobrouterconnector_domain_model_connection_%d.xml', \sys_get_temp_dir(), uniqid());
        \file_put_contents($fixturePath, $fixture);

        (new TestBase())->importXmlDatabaseFixture($fixturePath);
    }

    public function createMockServerExpectation(string $mockServerBaseUrl, string $username, string $password): void
    {
        $body = [
            'httpRequest' => [
                'method' => 'POST',
                'path' => '/api/rest/v2/application/tokens',
                'body' => \sprintf(
                    '{"username":"%s","password":"%s","lifetime":10}',
                    $username,
                    $password
                ),
            ],
            'httpResponse' => [
                'statusCode' => 201,
                'headers' => [
                    'content-type' => ['application/json'],
                    'x-jobrouter-version' => ['5.1.5'],
                ],
                'body' => '{"tokens":["testtoken"]}',
            ],
            'times' => [
                'remainingTimes' => 1,
            ],
        ];

        $content = new Stream('php://temp', 'rw');
        $content->write(\json_encode($body));

        $expectationUrl = \rtrim($mockServerBaseUrl, '/') . '/mockserver/expectation';
        $request = (new RequestFactory())
            ->createRequest('PUT', $expectationUrl)
            ->withBody($content);

        $client = GeneralUtility::makeInstance(ClientInterface::class);
        $client->sendRequest($request);
    }
}
