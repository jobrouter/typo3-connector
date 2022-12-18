<?php

declare(strict_types=1);

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Brotkrueml\JobRouterConnector\Controller\ConnectionListController;
use Brotkrueml\JobRouterConnector\Extension;

return [
    Extension::MODULE_NAME => [
        'path' => '/jobrouter/connections',
        'target' => ConnectionListController::class . '::handleRequest',
    ],
];
