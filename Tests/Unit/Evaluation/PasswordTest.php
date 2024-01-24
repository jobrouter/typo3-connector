<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Connector\Tests\Unit\Evaluation;

use JobRouter\AddOn\Typo3Connector\Evaluation\Password;
use JobRouter\AddOn\Typo3Connector\Service\Crypt;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class PasswordTest extends TestCase
{
    private Password $subject;
    private Crypt&MockObject $cryptMock;

    protected function setUp(): void
    {
        $this->cryptMock = $this->createMock(Crypt::class);

        $this->subject = new Password($this->cryptMock);
    }

    #[Test]
    public function evaluateFieldValueReturnsObfuscatedValueWhenAlreadyObfuscated(): void
    {
        $value = Password::OBFUSCATED_VALUE;

        $actual = $this->subject->evaluateFieldValue($value);

        self::assertSame($value, $actual);
    }

    #[Test]
    public function evaluateFieldValueWithValueNotObfuscatedReturnsEncryptedValue(): void
    {
        $encryptedValue = 'encrypted-value';
        $cleartextValue = 'cleartext-value';

        $this->cryptMock
            ->method('encrypt')
            ->with($cleartextValue)
            ->willReturn($encryptedValue);

        $actual = $this->subject->evaluateFieldValue($cleartextValue);

        self::assertSame($encryptedValue, $actual);
    }

    #[Test]
    public function deevaluateFieldValueReturnsEmptyStringWhenGivenValueIsEmptyString(): void
    {
        $actual = $this->subject->deevaluateFieldValue([
            'value' => '',
        ]);

        self::assertSame('', $actual);
    }

    #[Test]
    public function deevaluateFieldValueReturnsObfuscatedValueWhenGivenValueIsNotEmpty(): void
    {
        $actual = $this->subject->deevaluateFieldValue([
            'value' => 'some value',
        ]);

        self::assertSame(Password::OBFUSCATED_VALUE, $actual);
    }
}
