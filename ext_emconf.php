<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'JobRouter Connector',
    'description' => 'Define connections from TYPO3 to JobRouter digitisation platform',
    'category' => 'module',
    'author' => 'Chris Müller',
    'author_email' => 'typo3@krue.ml',
    'state' => 'beta',
    'version' => '0.9.0',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-9.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => ['Brotkrueml\\JobRouterConnector\\' => 'Classes']
    ],
];
