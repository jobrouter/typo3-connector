<?php
declare(strict_types = 1);

namespace Brotkrueml\JobRouterConnector\Utility;

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @internal
 */
class FileUtility
{
    public function getAbsoluteKeyPath(bool $errorOnNonExistingFile = true): string
    {
        $configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $keyPath = $configuration->get('jobrouter_connector', 'keyPath');

        if (!$keyPath) {
            throw new \RuntimeException(
                'The key file path is not defined correctly in the extension configuration!',
                1565992922
            );
        }

        $absoluteKeyPath = Environment::getProjectPath() . DIRECTORY_SEPARATOR . $keyPath;

        if ($errorOnNonExistingFile && !\file_exists($absoluteKeyPath)) {
            throw new \RuntimeException(
                'The key file is not available!',
                1565992923
            );
        }

        return $absoluteKeyPath;
    }
}
