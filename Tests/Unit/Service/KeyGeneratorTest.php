<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Tests\Unit\Service;

use Brotkrueml\JobRouterConnector\Exception\KeyFileException;
use Brotkrueml\JobRouterConnector\Exception\KeyGenerationException;
use Brotkrueml\JobRouterConnector\Service\Crypt;
use Brotkrueml\JobRouterConnector\Service\FileService;
use Brotkrueml\JobRouterConnector\Service\KeyGenerator;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

class KeyGeneratorTest extends TestCase
{
    private string $keyPath;
    private FileService&Stub $fileServiceStub;
    private Crypt&Stub $cryptStub;
    private KeyGenerator $subject;

    protected function setUp(): void
    {
        vfsStream::setup('project-dir');
        $this->keyPath = vfsStream::url('project-dir') . '/.key';
        $this->fileServiceStub = $this->createStub(FileService::class);
        $this->cryptStub = $this->createStub(Crypt::class);

        $this->subject = new KeyGenerator($this->cryptStub, $this->fileServiceStub);
    }

    /**
     * @test
     */
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

    /**
     * @test
     */
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

    /**
     * @test
     */
    public function generateAndStoreKeyThrowsExceptionIfKeyCannotBeWritten(): void
    {
        $this->expectException(KeyGenerationException::class);
        $this->expectExceptionCode(1603475037);

        $baseDir = vfsStream::url('project-dir') . '/some_folder';
        \mkdir($baseDir);
        \chmod($baseDir, 0444);

        $this->fileServiceStub
            ->method('getAbsoluteKeyPath')
            ->with(false)
            ->willReturn($baseDir . '/.key');

        $this->subject->generateAndStoreKey();
    }

    /**
     * @test
     */
    public function generateAndStoreKeyIsSuccessful(): void
    {
        $this->fileServiceStub
            ->method('getAbsoluteKeyPath')
            ->with(false)
            ->willReturn($this->keyPath);

        $this->cryptStub
            ->method('generateKey')
            ->willReturn('some key');

        $this->subject->generateAndStoreKey();

        self::assertFileExists($this->keyPath);
        self::assertStringEqualsFile($this->keyPath, 'some key');
    }
}
