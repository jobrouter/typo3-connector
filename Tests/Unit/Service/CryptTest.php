<?php
declare(strict_types=1);

namespace Brotkrueml\JobRouterConnector\Tests\Unit\Service;

use Brotkrueml\JobRouterConnector\Exception\CryptException;
use Brotkrueml\JobRouterConnector\Service\Crypt;
use Brotkrueml\JobRouterConnector\Utility\FileUtility;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class CryptTest extends TestCase
{
    /** @var vfsStreamDirectory */
    private $root;

    /** @var Crypt */
    private $subject;

    public function setUp(): void
    {
        $this->root = vfsStream::setup('project-dir');
    }

    /**
     * @test
     */
    public function generateKeyReturnsAKey(): void
    {
        $subject = new Crypt();

        $actual = $subject->generateKey();
        $actualBase64Decoded = \base64_decode($actual);

        $this->assertNotEmpty($actual);
        $this->assertSame(32, strlen($actualBase64Decoded));
    }

    /**
     * @test
     */
    public function encryptAndDecrypt(): void
    {
        $this->initialiseSubjectWithKeyPath();

        $value = 'this-is-the-value';

        $actualEncrypted = $this->subject->encrypt($value);
        $actualDecrypted = $this->subject->decrypt($actualEncrypted);

        $this->assertSame($value, $actualDecrypted);
    }

    /**
     * @test
     */
    public function encryptShouldThrowExceptionIfKeyDoesNotExist(): void
    {
        $this->expectException(CryptException::class);
        $this->expectExceptionCode(1565993703);

        $this->initialiseSubjectWithKeyPath(false);

        $this->subject->encrypt('some-value');
    }

    /**
     * @test
     */
    public function decryptThrowsExceptionOnNotBase64EncodedValueGiven(): void
    {
        $this->expectException(CryptException::class);
        $this->expectExceptionCode(1565993704);

        $this->initialiseSubjectWithKeyPath();

        $this->subject->decrypt('some-value');
    }

    /**
     * @test
     */
    public function decryptThrowsExceptionOnDecryptionFailure(): void
    {
        $this->expectException(CryptException::class);
        $this->expectExceptionCode(1565993704);

        $this->initialiseSubjectWithKeyPath();

        // base64 value is too short for a nonce embedded
        $this->subject->decrypt(\base64_encode('some-value'));
    }

    protected function initialiseSubjectWithKeyPath(bool $fileShouldExist = true): void
    {
        $keyPath = vfsStream::url('project-dir') . '/.jobrouter-key';

        $fileUtilityMock = $this->createMock(FileUtility::class);
        $fileUtilityMock
            ->method('getAbsoluteKeyPath')
            ->willReturn($keyPath);

        $this->subject = new Crypt($fileUtilityMock);

        if ($fileShouldExist) {
            \file_put_contents($keyPath, $this->subject->generateKey());
        }
    }
}
