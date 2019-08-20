<?php
declare(strict_types = 1);

namespace Brotkrueml\JobRouterConnector\Evaluation;

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Brotkrueml\JobRouterConnector\Exception\CryptException;
use Brotkrueml\JobRouterConnector\Service\Crypt;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class Password
{
    /** @var Crypt */
    private $cryptService;

    public function __construct(Crypt $crypt = null)
    {
        $this->cryptService = $crypt ?? GeneralUtility::makeInstance(Crypt::class);
    }

    public function evaluateFieldValue(string $value): string
    {
        try {
            $this->cryptService->decrypt($value);

            // The password is already encrypted
            return $value;
        } catch (CryptException $e) {
            // Do nothing
        }

        return $this->cryptService->encrypt($value);
    }
}
