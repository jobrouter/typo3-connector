<?php
declare(strict_types = 1);

namespace Brotkrueml\JobRouterConnector\Tests\Unit\Evaluation;

use Brotkrueml\JobRouterConnector\Evaluation\Password;
use Brotkrueml\JobRouterConnector\Exception\CryptException;
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
        $encryptedValue = 'encrypted-value';

        $this->cryptMock
            ->expects($this->once())
            ->method('decrypt')
            ->with($encryptedValue);

        $this->cryptMock
            ->expects($this->never())
            ->method('encrypt');

        $actual = $this->subject->evaluateFieldValue($encryptedValue);

        $this->assertSame($encryptedValue, $actual);
    }

    /**
     * @test
     */
    public function evaluateFieldValueWithClearTextValueReturnsEncryptedValue(): void
    {
        $encryptedValue = 'encrypted-value';
        $cleartextValue = 'cleartext-value';

        $this->cryptMock
            ->expects($this->once())
            ->method('decrypt')
            ->with($cleartextValue)
            ->willThrowException(new CryptException());

        $this->cryptMock
            ->expects($this->once())
            ->method('encrypt')
            ->with($cleartextValue)
            ->willReturn($encryptedValue);

        $actual = $this->subject->evaluateFieldValue($cleartextValue);

        $this->assertSame($encryptedValue, $actual);
    }
}
