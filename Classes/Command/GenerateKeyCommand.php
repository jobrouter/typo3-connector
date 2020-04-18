<?php
declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Command;

use Brotkrueml\JobRouterConnector\Service\Crypt;
use Brotkrueml\JobRouterConnector\Utility\FileUtility;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @internal
 */
final class GenerateKeyCommand extends Command
{
    public const EXIT_CODE_OK = 0;
    public const EXIT_CODE_KEY_FILE_WRONG_PATH = 1;
    public const EXIT_CODE_KEY_FILE_EXISTS = 2;
    public const EXIT_CODE_KEY_FILE_CANNOT_BE_WRITTEN = 3;

    /** @var Crypt */
    private $crypt;

    /** @var FileUtility */
    private $fileUtility;

    public function __construct(Crypt $crypt, FileUtility $fileUtility)
    {
        $this->crypt = $crypt;
        $this->fileUtility = $fileUtility;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Generates a random key for encrypting and decrypting connection passwords');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $outputStyle = new SymfonyStyle($input, $output);

        try {
            $absolutePath = $this->fileUtility->getAbsoluteKeyPath(false);
        } catch (\Throwable $e) {
            $outputStyle->error(sprintf('The key file path is not defined correctly in the extension configuration!'));
            return self::EXIT_CODE_KEY_FILE_WRONG_PATH;
        }

        if (\file_exists($absolutePath)) {
            $outputStyle->error(sprintf('The key file "%s" already exists!', $absolutePath));
            return self::EXIT_CODE_KEY_FILE_EXISTS;
        }

        if (false === \file_put_contents($absolutePath, $this->crypt->generateKey())) {
            $outputStyle->error(sprintf('The key file "%s" could not be written!', $absolutePath));
            return self::EXIT_CODE_KEY_FILE_CANNOT_BE_WRITTEN;
        }

        $outputStyle->success(sprintf('Key was generated and stored into "%s"', $absolutePath));

        return self::EXIT_CODE_OK;
    }
}
