<?php
declare(strict_types=1);

namespace Brotkrueml\JobRouterConnector\Tests\Unit\Domain\Model;

use Brotkrueml\JobRouterConnector\Domain\Model\Connection;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    private Connection $subject;

    protected function setUp(): void
    {
        $this->subject = new Connection();
    }

    /**
     * @test
     */
    public function initialNameIsEmptyString(): void
    {
        $actual = $this->subject->getName();

        self::assertSame('', $actual);
    }

    /**
     * @test
     */
    public function setAndGetName(): void
    {
        $this->subject->setName('some-name');

        $actual = $this->subject->getName();

        self::assertSame('some-name', $actual);
    }

    /**
     * @test
     */
    public function initialBaseUrlIsEmptyString(): void
    {
        $actual = $this->subject->getBaseUrl();

        self::assertSame('', $actual);
    }

    /**
     * @test
     */
    public function setAndGetBaseUrl(): void
    {
        $this->subject->setBaseUrl('http://example.org/');

        $actual = $this->subject->getBaseUrl();

        self::assertSame('http://example.org/', $actual);
    }

    /**
     * @test
     */
    public function initialUsernameIsEmptyString(): void
    {
        $actual = $this->subject->getUsername();

        self::assertSame('', $actual);
    }

    /**
     * @test
     */
    public function setAndGetUsername(): void
    {
        $this->subject->setUsername('some-username');

        $actual = $this->subject->getUsername();

        self::assertSame('some-username', $actual);
    }

    /**
     * @test
     */
    public function initialPasswordIsEmptyString(): void
    {
        $actual = $this->subject->getPassword();

        self::assertSame('', $actual);
    }

    /**
     * @test
     */
    public function setAndGetPassword(): void
    {
        $this->subject->setPassword('some-password');

        $actual = $this->subject->getPassword();

        self::assertSame('some-password', $actual);
    }

    /**
     * @test
     */
    public function initialDisabledIsFalse(): void
    {
        $actual = $this->subject->isDisabled();

        self::assertFalse($actual);
    }

    /**
     * @test
     */
    public function setAndIsDisabled(): void
    {
        $this->subject->setDisabled(true);

        $actual = $this->subject->isDisabled();

        self::assertTrue($actual);
    }
}
