<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use JobRouter\AddOn\Typo3Connector\Controller\ConnectionTestController;

return [
    'jobrouter_connection_test' => [
        'path' => '/jobrouter/connection/test',
        'target' => ConnectionTestController::class,
    ],
];
