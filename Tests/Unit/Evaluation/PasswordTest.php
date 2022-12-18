<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Tests\Unit\Evaluation;

use Brotkrueml\JobRouterConnector\Evaluation\Password;
use Brotkrueml\JobRouterConnector\Service\Crypt;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    private Password $subject;

    /**
     * @var MockObject&Crypt
     */
    private MockObject $cryptMock;

    protected function setUp(): void
    {
        $this->cryptMock = $this->createMock(Crypt::class);

        $this->subject = new Password($this->cryptMock);
    }

    /**
     * @test
     */
    public function evaluateFieldValueReturnsObfuscatedValueWhenAlreadyObfuscated(): void
    {
        $value = Password::OBFUSCATED_VALUE;

        $actual = $this->subject->evaluateFieldValue($value);

        self::assertSame($value, $actual);
    }

    /**
     * @test
     */
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

    /**
     * @test
     */
    public function deevaluateFieldValueReturnsEmptyStringWhenGivenValueIsEmptyString(): void
    {
        $actual = $this->subject->deevaluateFieldValue([
            'value' => '',
        ]);

        self::assertSame('', $actual);
    }

    /**
     * @test
     */
    public function deevaluateFieldValueReturnsObfuscatedValueWhenGivenValueIsNotEmpty(): void
    {
        $actual = $this->subject->deevaluateFieldValue([
            'value' => 'some value',
        ]);

        self::assertSame(Password::OBFUSCATED_VALUE, $actual);
    }
}
