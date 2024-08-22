<?php

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use JobRouter\AddOn\Typo3Connector\Extension;

return [
    'dependencies' => [
        'core',
        'backend',
    ],
    'imports' => [
        '@jobrouter/connector/' => 'EXT:' . Extension::KEY . '/Resources/Public/JavaScript/',
    ],
];
