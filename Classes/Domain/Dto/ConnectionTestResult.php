<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Connector\Domain\Dto;

/**
 * @internal
 */
final class ConnectionTestResult
{
    public function __construct(
        private readonly string $errorMessage,
    ) {}

    public function toJson(): string
    {
        $result = new \stdClass();

        if ($this->errorMessage === '') {
            $result->check = 'ok';
        } else {
            $result->error = $this->errorMessage;
        }

        return \json_encode($result, \JSON_THROW_ON_ERROR);
    }
}
