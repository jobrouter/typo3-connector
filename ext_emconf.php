<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'JobRouter Connector',
    'description' => 'Define connections from TYPO3 to JobRouter® digitisation platform',
    'category' => 'module',
    'author' => 'Chris Müller',
    'author_email' => 'typo3@krue.ml',
    'state' => 'stable',
    'version' => '1.1.0-dev',
    'constraints' => [
        'depends' => [
            'php' => '7.3.0-0.0.0',
            'typo3' => '10.4.11-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'jobrouter_data' => '',
            'jobrouter_process' => '',
        ],
    ],
    'autoload' => [
        'psr-4' => ['Brotkrueml\\JobRouterConnector\\' => 'Classes']
    ],
];
