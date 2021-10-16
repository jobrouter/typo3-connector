<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Service;

use Brotkrueml\JobRouterConnector\Exception\CryptException;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @internal
 */
class Crypt implements SingletonInterface
{
    /**
     * @var FileService
     */
    private $fileService;

    public function __construct(FileService $fileService = null)
    {
        $this->fileService = $fileService ?? GeneralUtility::makeInstance(FileService::class);
    }

    public function encrypt(string $value): string
    {
        try {
            $nonce = \random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $cipherText = \sodium_crypto_secretbox($value, $nonce, $this->getKey());
        } catch (\Exception $e) {
            throw new CryptException(
                'The value could not be encrypted!',
                1565993703,
                $e
            );
        }

        return \base64_encode($nonce . $cipherText);
    }

    public function decrypt(string $value): string
    {
        $decoded = \base64_decode($value);
        $nonce = \mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $cipherText = \mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

        try {
            $clearText = \sodium_crypto_secretbox_open($cipherText, $nonce, $this->getKey());
        } catch (\Exception $e) {
            throw new CryptException(
                'The value could not be decrypted!',
                1565993704,
                $e
            );
        }

        if ($clearText === false) {
            throw new CryptException(
                'The value could not be decrypted!',
                1565993705
            );
        }

        return $clearText;
    }

    public function generateKey(): string
    {
        try {
            return \base64_encode(\random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES));
        } catch (\Exception $e) {
            throw new CryptException(
                'The key could not be generated!',
                1565993706,
                $e
            );
        }
    }

    private function getKey(): string
    {
        $key = \file_get_contents($this->fileService->getAbsoluteKeyPath());
        if ($key === false) {
            throw new CryptException(
                'The key could not be retrieved!',
                1565993707
            );
        }

        return \base64_decode($key);
    }
}
