<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Connector\Tests\Unit\Domain\Dto;

use JobRouter\AddOn\Typo3Connector\Domain\Dto\ConnectionTestResult;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ConnectionTestResultTest extends TestCase
{
    #[Test]
    public function toJsonReturnsCorrectJsonWhenNoErrorMessageIsGiven(): void
    {
        $subject = new ConnectionTestResult('');

        self::assertJsonStringEqualsJsonString('{"check": "ok"}', $subject->toJson());
    }

    #[Test]
    public function toJsonReturnsCorrectJsonWhenErrorMessageIsGiven(): void
    {
        $subject = new ConnectionTestResult('some error message');

        self::assertJsonStringEqualsJsonString('{"error": "some error message"}', $subject->toJson());
    }
}
