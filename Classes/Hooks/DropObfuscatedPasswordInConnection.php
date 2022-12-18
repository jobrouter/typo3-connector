<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Hooks;

use Brotkrueml\JobRouterConnector\Evaluation\Password;
use TYPO3\CMS\Core\DataHandling\DataHandler;

/**
 * @internal
 */
final class DropObfuscatedPasswordInConnection
{
    /**
     * @param array<string, mixed> $incomingFieldArray
     */
    public function processDatamap_preProcessFieldArray(array &$incomingFieldArray, string $table, int|string $id, DataHandler $dataHandler): void
    {
        if ($table !== 'tx_jobrouterconnector_domain_model_connection') {
            return;
        }

        if ($incomingFieldArray['password'] === Password::OBFUSCATED_VALUE) {
            unset($incomingFieldArray['password']);
        }
    }
}
