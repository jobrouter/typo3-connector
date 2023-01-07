<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector\Exception;

final class ConnectionNotFoundException extends \RuntimeException
{
    public static function forUid(int $uid): self
    {
        return new self(
            \sprintf(
                'Connection with uid "%d" not found.',
                $uid,
            ),
            1672478103,
        );
    }
}
