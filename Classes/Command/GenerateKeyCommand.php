<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Command;

use Brotkrueml\JobRouterConnector\Exception\KeyGenerationException;
use Brotkrueml\JobRouterConnector\Service\KeyGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @internal
 */
final class GenerateKeyCommand extends Command
{
    public function __construct(
        private readonly KeyGenerator $keyGenerator,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $outputStyle = new SymfonyStyle($input, $output);

        try {
            $this->keyGenerator->generateAndStoreKey();
        } catch (KeyGenerationException $e) {
            $outputStyle->error($e->getMessage());

            return self::FAILURE;
        }

        $outputStyle->success('Key was generated successfully');

        return self::SUCCESS;
    }
}
