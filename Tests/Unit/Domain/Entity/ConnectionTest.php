<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Tests\Unit\Domain\Entity;

use Brotkrueml\JobRouterConnector\Domain\Entity\Connection;
use PHPUnit\Framework\TestCase;

final class ConnectionTest extends TestCase
{
    /**
     * @test
     */
    public function fromArray(): void
    {
        $data = [
            'uid' => '42',
            'name' => 'some name',
            'handle' => 'some_handle',
            'base_url' => 'https://example.org/',
            'username' => 'someuser',
            'password' => 'someencryptedpassword',
            'timeout' => '60',
            'verify' => '1',
            'proxy' => 'https://proxy.example.net/',
            'jobrouter_version' => '2022.4.1',
            'disabled' => '0',
        ];

        $actual = Connection::fromArray($data);

        self::assertSame(42, $actual->uid);
        self::assertSame('some name', $actual->name);
        self::assertSame('some_handle', $actual->handle);
        self::assertSame('https://example.org/', $actual->baseUrl);
        self::assertSame('someuser', $actual->username);
        self::assertSame('someencryptedpassword', $actual->encryptedPassword);
        self::assertSame(60, $actual->timeout);
        self::assertTrue($actual->verify);
        self::assertSame('https://proxy.example.net/', $actual->proxy);
        self::assertSame('2022.4.1', $actual->jobrouterVersion);
        self::assertFalse($actual->disabled);
    }
}
