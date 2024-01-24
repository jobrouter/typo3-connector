<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Connector\Exception;

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

    public static function forHandle(string $handle): self
    {
        return new self(
            \sprintf(
                'Connection with handle "%d" not found.',
                $handle,
            ),
            1672478104,
        );
    }
}
