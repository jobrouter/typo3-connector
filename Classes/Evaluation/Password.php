<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Evaluation;

use Brotkrueml\JobRouterConnector\Exception\CryptException;
use Brotkrueml\JobRouterConnector\Service\Crypt;

/**
 * @internal
 */
final class Password
{
    public function __construct(
        private readonly Crypt $cryptService
    ) {
    }

    public function evaluateFieldValue(string $value): string
    {
        try {
            $this->cryptService->decrypt($value);

            // The password is already encrypted
            return $value;
        } catch (CryptException) {
            // Do nothing
        }

        return $this->cryptService->encrypt($value);
    }
}
