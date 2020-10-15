<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Brotkrueml\JobRouterConnector;

/**
 * @internal
 */
final class Extension
{
    public const KEY = 'jobrouter_connector';

    public const MODULE_GROUP = 'jobrouter';

    private const LANGUAGE_PATH = 'LLL:EXT:' . self::KEY . '/Resources/Private/Language/';
    public const LANGUAGE_PATH_BACKEND_MODULE = self::LANGUAGE_PATH . 'BackendModule.xlf';
    public const LANGUAGE_PATH_DATABASE = self::LANGUAGE_PATH . 'Database.xlf';
}
