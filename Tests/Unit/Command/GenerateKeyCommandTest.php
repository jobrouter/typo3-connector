<?php
declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Tests\Unit\Command;

use Brotkrueml\JobRouterConnector\Command\GenerateKeyCommand;
use Brotkrueml\JobRouterConnector\Exception\KeyFileException;
use Brotkrueml\JobRouterConnector\Service\Crypt;
use Brotkrueml\JobRouterConnector\Utility\FileUtility;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateKeyCommandTest extends TestCase
{
    /** @var CommandTester */
    private $commandTester;

    /** @var ObjectProphecy */
    private $fileUtility;

    /** @var string */
    private $keyPath = '';

    protected function setUp(): void
    {
        vfsStream::setup('project-dir');
        $this->keyPath = vfsStream::url('project-dir') . '/.key';

        $this->fileUtility = $this->prophesize(FileUtility::class);

        $command = new GenerateKeyCommand(new Crypt(), $this->fileUtility->reveal());
        $this->commandTester = new CommandTester($command);
    }

    /**
     * @test
     */
    public function keyIsSuccessfullyStored(): void
    {
        $this->fileUtility->getAbsoluteKeyPath(false)->willReturn($this->keyPath);

        $this->commandTester->execute([]);

        self::assertStringStartsWith('[OK]', trim($this->commandTester->getDisplay()));
        self::assertSame(GenerateKeyCommand::EXIT_CODE_OK, $this->commandTester->getStatusCode());
        self::assertFileExists($this->keyPath);
    }

    /**
     * @test
     */
    public function returnErrorWhenExtensionConfigurationIsNotDefinedCorrectly(): void
    {
        $this->fileUtility->getAbsoluteKeyPath(false)->willThrow(KeyFileException::class);

        $this->commandTester->execute([]);

        self::assertStringStartsWith('[ERROR]', trim($this->commandTester->getDisplay()));
        self::assertSame(GenerateKeyCommand::EXIT_CODE_KEY_FILE_WRONG_PATH, $this->commandTester->getStatusCode());
    }

    /**
     * @test
     */
    public function returnErrorWhenKeyFileAlreadyExists(): void
    {
        \touch($this->keyPath);

        $this->fileUtility->getAbsoluteKeyPath(false)->willReturn($this->keyPath);

        $this->commandTester->execute([]);

        self::assertStringStartsWith('[ERROR]', trim($this->commandTester->getDisplay()));
        self::assertSame(GenerateKeyCommand::EXIT_CODE_KEY_FILE_EXISTS, $this->commandTester->getStatusCode());
    }
}
