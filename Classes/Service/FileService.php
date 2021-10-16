<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Service;

use Brotkrueml\JobRouterConnector\Exception\KeyFileException;
use Brotkrueml\JobRouterConnector\Extension;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @internal
 */
class FileService
{
    public function getAbsoluteKeyPath(bool $errorOnNonExistingFile = true): string
    {
        $configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $keyPath = $configuration->get(Extension::KEY, 'keyPath');

        if (! $keyPath) {
            throw new KeyFileException(
                'The key file path is not defined correctly in the extension configuration!',
                1565992922
            );
        }

        $folder = Environment::getProjectPath();
        if (! Environment::isComposerMode()) {
            // In classic installation the project path is the public folder
            $folder = \dirname($folder);
        }
        $absoluteKeyPath = $folder . DIRECTORY_SEPARATOR . $keyPath;
        if (! $errorOnNonExistingFile) {
            return $absoluteKeyPath;
        }
        if (\is_file($absoluteKeyPath)) {
            return $absoluteKeyPath;
        }

        throw new KeyFileException(
            'The key file is not available!',
            1565992923
        );
    }
}
