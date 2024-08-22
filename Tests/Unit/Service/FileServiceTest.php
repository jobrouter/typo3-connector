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
use JobRouter\AddOn\Typo3Connector\Service\FileService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\ApplicationContext;
use TYPO3\CMS\Core\Core\Environment;

#[CoversClass(FileService::class)]
final class FileServiceTest extends TestCase
{
    private ExtensionConfiguration&MockObject $extensionConfigurationMock;
    private FileService $subject;
    private string $rootDir;

    protected function setUp(): void
    {
        $this->extensionConfigurationMock = $this->createMock(ExtensionConfiguration::class);
        $this->subject = new FileService($this->extensionConfigurationMock);

        $this->rootDir = \sys_get_temp_dir() . '/fileservicetest';
        if (! \is_dir($this->rootDir)) {
            \mkdir($this->rootDir);
        }
    }

    #[Test]
    public function getAbsoluteKeyPathThrowsExceptionOnNotDefinedKeyPath(): void
    {
        $this->expectException(KeyFileException::class);
        $this->expectExceptionCode(1565992922);

        $this->extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with('jobrouter_connector', 'keyPath')
            ->willReturn('');

        $this->subject->getAbsoluteKeyPath();
    }

    #[Test]
    public function getAbsoluteKeyPathThrowsExceptionOnNonExistingFile(): void
    {
        $this->expectException(KeyFileException::class);
        $this->expectExceptionCode(1565992923);

        $this->extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with('jobrouter_connector', 'keyPath')
            ->willReturn('.non-existing-file');

        $this->initializeEnvironment();

        $this->subject->getAbsoluteKeyPath();
    }

    #[Test]
    public function getAbsoluteKeyPathReturnsNonExistingPathIfErrorIsOmitted(): void
    {
        $this->extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with('jobrouter_connector', 'keyPath')
            ->willReturn('.non-existing-file');

        $this->initializeEnvironment();

        $actual = $this->subject->getAbsoluteKeyPath(false);

        self::assertSame($this->rootDir . '/.non-existing-file', $actual);
    }

    #[Test]
    public function getAbsoluteKeyPathReturnsPathCorrectly(): void
    {
        $keyFile = '.jobrouter-key-' . \uniqid();
        $keyFilePath = $this->rootDir . '/' . $keyFile;
        \touch($keyFilePath);

        $this->extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with('jobrouter_connector', 'keyPath')
            ->willReturn($keyFile);

        $this->initializeEnvironment();

        $actual = $this->subject->getAbsoluteKeyPath();

        self::assertSame($keyFilePath, $actual);

        \unlink($keyFilePath);
    }

    #[Test]
    public function getAbsoluteKeyPathReturnsPathCorrectlyIfNotInComposerMode(): void
    {
        $keyFile = '.jobrouter-key-' . \uniqid();
        $keyFilePath = $this->rootDir . '/' . $keyFile;
        \touch($keyFilePath);

        $this->extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with('jobrouter_connector', 'keyPath')
            ->willReturn($keyFile);

        $this->initializeEnvironment(false, $this->rootDir . '/some-folder-' . \uniqid());

        $actual = $this->subject->getAbsoluteKeyPath();

        self::assertSame($keyFilePath, $actual);
    }

    protected function initializeEnvironment(bool $isComposerMode = true, string $projectPath = ''): void
    {
        Environment::initialize(
            new ApplicationContext('Testing'),
            false,
            $isComposerMode,
            $projectPath ?: $this->rootDir,
            '',
            '',
            '',
            '',
            '',
        );
    }
}
