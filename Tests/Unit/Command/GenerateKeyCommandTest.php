<?php
declare(strict_types=1);

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
    private CommandTester $commandTester;
    private ObjectProphecy $fileUtility;
    private string $keyPath = '';

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
        self::assertSame(0, $this->commandTester->getStatusCode());
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
        self::assertSame(1, $this->commandTester->getStatusCode());
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
        self::assertSame(2, $this->commandTester->getStatusCode());
    }
}
