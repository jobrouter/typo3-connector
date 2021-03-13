<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Tests\Unit\Utility;

use Brotkrueml\JobRouterConnector\Exception\KeyFileException;
use Brotkrueml\JobRouterConnector\Utility\FileUtility;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\ApplicationContext;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @runTestsInSeparateProcesses
 */
class FileUtilityTest extends TestCase
{
    protected const ROOT_DIR = 'project-dir';

    /** @var vfsStreamDirectory */
    protected $root;

    /** @var FileUtility */
    protected $subject;

    protected function setUp(): void
    {
        $this->root = vfsStream::setup(self::ROOT_DIR);
        $this->subject = new FileUtility();
    }

    /**
     * @test
     */
    public function getAbsoluteKeyPathThrowsExceptionOnNotDefinedKeyPath(): void
    {
        $this->expectException(KeyFileException::class);
        $this->expectExceptionCode(1565992922);

        GeneralUtility::addInstance(
            ExtensionConfiguration::class,
            $this->getExtensionConfigurationMock('')
        );

        $this->subject->getAbsoluteKeyPath();
    }

    /**
     * @test
     */
    public function getAbsoluteKeyPathThrowsExceptionOnNonExistingFile(): void
    {
        $this->expectException(KeyFileException::class);
        $this->expectExceptionCode(1565992923);

        GeneralUtility::addInstance(
            ExtensionConfiguration::class,
            $this->getExtensionConfigurationMock(
                '.non-existing-file'
            )
        );

        $this->initializeEnvironment();

        $this->subject->getAbsoluteKeyPath();
    }

    /**
     * @test
     */
    public function getAbsoluteKeyPathReturnsNonExistingPathIfErrorIsOmitted(): void
    {
        GeneralUtility::addInstance(
            ExtensionConfiguration::class,
            $this->getExtensionConfigurationMock(
                '.non-existing-file'
            )
        );

        $this->initializeEnvironment();

        $actual = $this->subject->getAbsoluteKeyPath(false);

        self::assertSame('vfs://' . self::ROOT_DIR . '/.non-existing-file', $actual);
    }

    /**
     * @test
     */
    public function getAbsoluteKeyPathReturnsPathCorrectly(): void
    {
        \touch(vfsStream::url(self::ROOT_DIR) . '/.jobrouter-key');

        GeneralUtility::addInstance(
            ExtensionConfiguration::class,
            $this->getExtensionConfigurationMock(
                '.jobrouter-key'
            )
        );

        $this->initializeEnvironment();

        $actual = $this->subject->getAbsoluteKeyPath();

        self::assertSame('vfs://' . self::ROOT_DIR . '/.jobrouter-key', $actual);
    }

    /**
     * @test
     */
    public function getAbsoluteKeyPathReturnsPathCorrectlyIfNotInComposerMode(): void
    {
        \touch(vfsStream::url(self::ROOT_DIR) . '/.jobrouter-key');

        GeneralUtility::addInstance(
            ExtensionConfiguration::class,
            $this->getExtensionConfigurationMock(
                '.jobrouter-key'
            )
        );

        $this->initializeEnvironment(false, vfsStream::url(self::ROOT_DIR) . '/some-folder');

        $actual = $this->subject->getAbsoluteKeyPath();

        self::assertSame('vfs://' . self::ROOT_DIR . '/.jobrouter-key', $actual);
    }

    protected function getExtensionConfigurationMock($returnedKeyPath): MockObject
    {
        /** @var MockObject|ExtensionConfiguration $extensionConfigurationMock */
        $extensionConfigurationMock = $this->createMock(ExtensionConfiguration::class);
        $extensionConfigurationMock
            ->expects(self::once())
            ->method('get')
            ->with('jobrouter_connector', 'keyPath')
            ->willReturn($returnedKeyPath);

        return $extensionConfigurationMock;
    }

    protected function initializeEnvironment(bool $isComposerMode = true, string $projectPath = ''): void
    {
        Environment::initialize(
            new ApplicationContext('Testing'),
            false,
            $isComposerMode,
            $projectPath ?: vfsStream::url(self::ROOT_DIR),
            '',
            '',
            '',
            '',
            ''
        );
    }
}
