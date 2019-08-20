<?php
declare(strict_types = 1);

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

    public function setUp(): void
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

        $this->assertStringStartsWith('[OK]', trim($actual));
        $this->assertFileExists($this->keyPath);
    }

    /**
     * @test
     */
    public function keyFileAlreadyExists(): void
    {
        \touch($this->keyPath);

        $this->commandTester->execute([]);

        $actual = $this->commandTester->getDisplay();

        $this->assertStringStartsWith('[ERROR]', trim($actual));
        $this->assertStringEndsWith('already exists!', trim($actual));
    }
}
