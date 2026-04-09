<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use JobRouter\AddOn\Typo3Connector\Extension;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Information\Typo3Version;

$icons = [
    'actions-jobrouter' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:' . Extension::KEY . '/Resources/Public/Icons/actions-jobrouter.svg',
    ],
    'jobrouter-modulegroup' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:' . Extension::KEY . '/Resources/Public/Icons/modulegroup-jobrouter.svg',
    ],
    'jobrouter-module-connector' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:' . Extension::KEY . '/Resources/Public/Icons/module-connector.svg',
    ],
];

// @todo Remove, once compatibility with TYPO3 v13 is removed
if ((new Typo3Version())->getMajorVersion() < 14) {
    $icons['jobrouter-modulegroup']['source'] = 'EXT:' . Extension::KEY . '/Resources/Public/Icons/modulegroup-jobrouter-v13.svg';
    $icons['jobrouter-module-connector']['source'] = 'EXT:' . Extension::KEY . '/Resources/Public/Icons/module-connector-v13.svg';
}

return $icons;
