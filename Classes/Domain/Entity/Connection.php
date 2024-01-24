<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace JobRouter\AddOn\Typo3Connector\Domain\Entity;

final class Connection
{
    private function __construct(
        public readonly int $uid,
        public readonly string $name,
        public string $handle,
        public string $baseUrl,
        public string $username,
        public string $encryptedPassword,
        public int $timeout,
        public bool $verify,
        public string $proxy,
        public string $jobrouterVersion,
        public bool $disabled,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (int)$data['uid'],
            $data['name'],
            $data['handle'],
            $data['base_url'],
            $data['username'],
            $data['password'],
            (int)$data['timeout'],
            (bool)$data['verify'],
            $data['proxy'],
            $data['jobrouter_version'],
            (bool)$data['disabled'],
        );
    }
}
