<?php
declare(strict_types=1);

namespace Brotkrueml\JobRouterConnector\Tests\Unit\Command;

use Brotkrueml\JobRouterConnector\Command\GenerateKeyCommand;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateKeyCommandTest extends TestCase
{
    /** @var vfsStreamDirectory */
    protected $root;

    /** @var CommandTester */
    private $commandTester;

    private $keyPath = '';

    protected function setUp(): void
    {
        $this->root = vfsStream::setup('project-dir');
        $this->keyPath = vfsStream::url('project-dir') . '/.key';

        $command = new GenerateKeyCommand();
        $command->setAbsoluteKeyPath($this->keyPath);

        $this->commandTester = new CommandTester($command);
    }

    /**
     * @test
     */
    public function keyIsSuccessfullyStored(): void
    {
        $this->commandTester->execute([]);

        $actual = $this->commandTester->getDisplay();

        self::assertStringStartsWith('[OK]', trim($actual));
        self::assertFileExists($this->keyPath);
    }

    /**
     * @test
     */
    public function keyFileAlreadyExists(): void
    {
        \touch($this->keyPath);

        $this->commandTester->execute([]);

        $actual = $this->commandTester->getDisplay();

        self::assertStringStartsWith('[ERROR]', trim($actual));
        self::assertStringEndsWith('already exists!', trim($actual));
    }
}
