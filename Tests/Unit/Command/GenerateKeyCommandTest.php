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
use Brotkrueml\JobRouterConnector\Exception\KeyGenerationException;
use Brotkrueml\JobRouterConnector\Service\KeyGenerator;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateKeyCommandTest extends TestCase
{
    /**
     * @var CommandTester
     */
    private $commandTester;

    /**
     * @var KeyGenerator|Stub
     */
    private $keyGeneratorStub;

    protected function setUp(): void
    {
        $this->keyGeneratorStub = $this->createStub(KeyGenerator::class);

        $command = new GenerateKeyCommand($this->keyGeneratorStub);
        $this->commandTester = new CommandTester($command);
    }

    /**
     * @test
     */
    public function returnSuccessIfKeyWasGeneratedSuccessfully(): void
    {
        $this->keyGeneratorStub
            ->method('generateAndStoreKey');

        $this->commandTester->execute([]);

        self::assertStringContainsString('[OK] Key was generated successfully', $this->commandTester->getDisplay());
        self::assertSame(0, $this->commandTester->getStatusCode());
    }

    /**
     * @test
     */
    public function returnErrorIfKeyGenerationThrowsException(): void
    {
        $this->keyGeneratorStub
            ->method('generateAndStoreKey')
            ->willThrowException(new KeyGenerationException('some error'));

        $this->commandTester->execute([]);

        self::assertStringContainsString('[ERROR] some error', $this->commandTester->getDisplay());
        self::assertSame(1, $this->commandTester->getStatusCode());
    }
}
