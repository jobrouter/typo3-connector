<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Connector\Tests\Unit\Service;

use JobRouter\AddOn\Typo3Connector\Exception\KeyFileException;
use JobRouter\AddOn\Typo3Connector\Exception\KeyGenerationException;
use JobRouter\AddOn\Typo3Connector\Service\Crypt;
use JobRouter\AddOn\Typo3Connector\Service\FileService;
use JobRouter\AddOn\Typo3Connector\Service\KeyGenerator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

final class KeyGeneratorTest extends TestCase
{
    private string $keyPath;
    private FileService&Stub $fileServiceStub;
    private Crypt&Stub $cryptStub;
    private KeyGenerator $subject;

    protected function setUp(): void
    {
        $this->keyPath = \tempnam(\sys_get_temp_dir(), 'keygeneratortest-');
        $this->fileServiceStub = self::createStub(FileService::class);
        $this->cryptStub = self::createStub(Crypt::class);

        $this->subject = new KeyGenerator($this->cryptStub, $this->fileServiceStub);
    }

    #[Test]
    public function generateAndStoreKeyThrowsExceptionWhenKeyFilePathIsNotDefinedCorrectly(): void
    {
        $this->expectException(KeyGenerationException::class);
        $this->expectExceptionCode(1603474945);

        $this->fileServiceStub
            ->method('getAbsoluteKeyPath')
            ->with(false)
            ->willThrowException(new KeyFileException());

        $this->subject->generateAndStoreKey();
    }

    #[Test]
    public function generateAndStoreKeyThrowsExceptionIfKeyAlreadyExists(): void
    {
        $this->expectException(KeyGenerationException::class);
        $this->expectExceptionCode(1603474997);

        $this->fileServiceStub
            ->method('getAbsoluteKeyPath')
            ->with(false)
            ->willReturn($this->keyPath);

        \touch($this->keyPath);

        $this->subject->generateAndStoreKey();
    }

    #[Test]
    public function generateAndStoreKeyThrowsExceptionIfKeyCannotBeWritten(): void
    {
        $this->expectException(KeyGenerationException::class);
        $this->expectExceptionCode(1603475037);

        $baseDir = \sys_get_temp_dir() . '/keygeneratortest-' . \uniqid();
        \mkdir($baseDir);
        \chmod($baseDir, 0444);

        $this->fileServiceStub
            ->method('getAbsoluteKeyPath')
            ->with(false)
            ->willReturn($baseDir . '/.key');

        $this->subject->generateAndStoreKey();
    }

    #[Test]
    public function generateAndStoreKeyIsSuccessful(): void
    {
        $keyPath = \sys_get_temp_dir() . '/keygeneratortest-' . \uniqid();

        $this->fileServiceStub
            ->method('getAbsoluteKeyPath')
            ->with(false)
            ->willReturn($keyPath);

        $this->cryptStub
            ->method('generateKey')
            ->willReturn('some key');

        $this->subject->generateAndStoreKey();

        self::assertFileExists($keyPath);
        self::assertStringEqualsFile($keyPath, 'some key');
    }
}
