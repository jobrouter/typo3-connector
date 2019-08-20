<?php
declare(strict_types = 1);

namespace Brotkrueml\JobRouterConnector\Tests\Unit\Evaluation;

use Brotkrueml\JobRouterConnector\Evaluation\Password;
use Brotkrueml\JobRouterConnector\Service\Crypt;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    /** @var Password */
    private $subject;

    /** @var MockObject|Crypt */
    private $cryptMock;

    public function setUp(): void
    {
        $this->cryptMock = $this->createMock(Crypt::class);

        $this->subject = new Password($this->cryptMock);
    }

    /**
     * @test
     */
    public function evaluateFieldValueReturnsSameEncryptedValueIfItsAlreadyEncrypted(): void
    {
        $this->cryptMock
            ->expects($this->once())
            ->method('decrypt')
            ->with('encrypted-value');

        $this->cryptMock
            ->expects($this->never())
            ->method('encrypt');

        $actual = $this->subject->evaluateFieldValue('encrypted-value');

        $this->assertSame('encrypted-value', $actual);
    }

    /**
     * @test
     */
    public function evaluateFieldValueWithClearTextValueReturnsEncryptedValue(): void
    {
        $this->cryptMock
            ->expects($this->once())
            ->method('decrypt')
            ->with('cleartext-value')
            ->willThrowException(new \RuntimeException());

        $this->cryptMock
            ->expects($this->once())
            ->method('encrypt')
            ->with('cleartext-value')
            ->willReturn('encrypted-value');

        $actual = $this->subject->evaluateFieldValue('cleartext-value');

        $this->assertSame('encrypted-value', $actual);
    }
}
