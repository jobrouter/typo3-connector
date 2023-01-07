<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Service;

use Brotkrueml\JobRouterConnector\Exception\KeyGenerationException;

class KeyGenerator
{
    public function __construct(
        private readonly Crypt $crypt,
        private readonly FileService $fileService,
    ) {
    }

    public function generateAndStoreKey(): void
    {
        try {
            $absolutePath = $this->fileService->getAbsoluteKeyPath(false);
        } catch (\Throwable $e) {
            throw new KeyGenerationException(
                'The key file path is not defined correctly in the extension configuration!',
                1603474945,
                $e,
            );
        }

        if (\file_exists($absolutePath)) {
            throw new KeyGenerationException(
                \sprintf('The key file "%s" already exists!', $absolutePath),
                1603474997,
            );
        }

        try {
            $this->writeKey($absolutePath, $this->crypt->generateKey());
        } catch (\Throwable $t) {
            throw new KeyGenerationException(
                \sprintf('The key file "%s" could not be written!', $absolutePath),
                1603475037,
                $t,
            );
        }
    }

    private function writeKey(string $path, string $key): void
    {
        \set_error_handler(static function (int $severity, string $message, string $file, int $line): never {
            throw new \ErrorException($message, $severity, $severity, $file, $line);
        });

        \file_put_contents($path, $key);

        \restore_error_handler();
    }
}
