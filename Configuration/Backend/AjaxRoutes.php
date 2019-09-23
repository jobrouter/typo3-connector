<?php
return [
    'jobrouter_connection_check' => [
        'path' => '/jobrouter/connection/check',
        'target' => \Brotkrueml\JobRouterConnector\Controller\ConnectionAjaxController::class . '::checkAction'
    ],
];
