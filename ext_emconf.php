<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'JobRouter Connector',
    'description' => 'Connect TYPO3 with the JobRouter® digitalisation platform',
    'category' => 'module',
    'author' => 'Chris Müller',
    'author_email' => 'typo3@krue.ml',
    'state' => 'stable',
    'version' => '2.0.0-dev',
    'constraints' => [
        'depends' => [
            'php' => '8.1.0-0.0.0',
            'typo3' => '11.5.0-11.5.99',
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
