<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'JobRouter Connector',
    'description' => 'Connect TYPO3 with the JobRouter® digitalisation platform',
    'category' => 'module',
    'author' => 'Chris Müller',
    'author_company' => 'JobRouter AG',
    'state' => 'stable',
    'version' => '3.0.1',
    'constraints' => [
        'depends' => [
            'php' => '8.1.0-0.0.0',
            'typo3' => '11.5.0-12.4.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'jobrouter_data' => '',
            'jobrouter_process' => '',
        ],
    ],
    'autoload' => [
        'psr-4' => ['JobRouter\\AddOn\\Typo3Connector\\' => 'Classes']
    ],
];
