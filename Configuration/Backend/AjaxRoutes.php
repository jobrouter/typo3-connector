<?php

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

return [
    'jobrouter_connection_check' => [
        'path' => '/jobrouter/connection/check',
        'target' => \Brotkrueml\JobRouterConnector\Controller\ConnectionAjaxController::class . '::checkAction'
    ],
];
