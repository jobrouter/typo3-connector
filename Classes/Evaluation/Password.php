<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Connector\Evaluation;

use JobRouter\AddOn\Typo3Connector\Service\Crypt;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

/**
 * @internal
 */
#[Autoconfigure(public: true)]
final class Password
{
    public const OBFUSCATED_VALUE = '********';

    public function __construct(
        private readonly Crypt $cryptService,
    ) {}

    public function evaluateFieldValue(string $value): string
    {
        if ($value === self::OBFUSCATED_VALUE) {
            return $value;
        }

        return $this->cryptService->encrypt($value);
    }

    /**
     * @param array{value: string} $parameters
     */
    public function deevaluateFieldValue(array $parameters): string
    {
        if ($parameters['value'] === '') {
            return '';
        }

        return self::OBFUSCATED_VALUE;
    }
}
