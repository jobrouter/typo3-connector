<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use JobRouter\AddOn\Typo3Connector\Controller\ConnectionListController;
use JobRouter\AddOn\Typo3Connector\Extension;

return [
    // "JobRouter" module group
    'jobrouter' => [
        'position' => [
            'before' => 'tools',
        ],
        'labels' => 'LLL:EXT:' . Extension::KEY . '/Resources/Private/Language/BackendModuleGroup.xlf',
        'iconIdentifier' => 'jobrouter-modulegroup',
    ],

    // "Connections" module
    Extension::MODULE_NAME => [
        'parent' => 'jobrouter',
        'position' => ['top'],
        'access' => 'admin',
        'workspaces' => 'live',
        'path' => '/module/jobrouter/connections',
        'labels' => Extension::LANGUAGE_PATH_BACKEND_MODULE,
        'iconIdentifier' => 'jobrouter-module-connector',
        'routes' => [
            '_default' => [
                'target' => ConnectionListController::class . '::handleRequest',
            ],
        ],
    ],
];
