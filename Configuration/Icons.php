<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

return [
    'jobrouter-modulegroup' => [
        'provider' => TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:' . Brotkrueml\JobRouterConnector\Extension::KEY . '/Resources/Public/Icons/modulegroup-jobrouter.svg',
    ],
    'jobrouter-module-connector' => [
        'provider' => TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:' . Brotkrueml\JobRouterConnector\Extension::KEY . '/Resources/Public/Icons/module-connector.svg',
    ],
];
