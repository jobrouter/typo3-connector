<?php
declare(strict_types=1);

namespace Brotkrueml\JobRouterConnector\Command;

/**
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */
use Brotkrueml\JobRouterConnector\Service\Crypt;
use Brotkrueml\JobRouterConnector\Utility\FileUtility;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class GenerateKeyCommand extends Command
{
    protected function configure(): void
    {
        $this->setDescription('Generates a random key for encrypting and decrypting connection passwords');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $outputStyle = new SymfonyStyle($input, $output);

        try {
            $absolutePath = $this->getAbsoluteKeyPath();
        } catch (\Throwable $e) {
            $outputStyle->error(sprintf('The key file path is not defined correctly in the extension configuration!'));
            return 1;
        }

        if (\file_exists($absolutePath)) {
            $outputStyle->error(sprintf('The key file "%s" already exists!', $absolutePath));
            return 2;
        }

        if (false === \file_put_contents($absolutePath, $this->getCrypt()->generateKey())) {
            $outputStyle->error(sprintf('The key file "%s" could not be written!', $absolutePath));
            return 3;
        }

        $outputStyle->success(sprintf('Key was generated and stored into "%s"', $absolutePath));

        return 0;
    }

    /**
     * @return Crypt
     * @norector Rector\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector
     */
    protected function getCrypt(): Crypt
    {
        return GeneralUtility::makeInstance(Crypt::class);
    }

    protected function getAbsoluteKeyPath(): string
    {
        $fileUtility = GeneralUtility::makeInstance(FileUtility::class);

        return $fileUtility->getAbsoluteKeyPath(false);
    }
}
