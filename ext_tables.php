<?php

defined('TYPO3') || die();

// Register "JobRouter" module group
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
    'jobrouter',
    '',
    'before:tools',
    null,
    [
        'iconIdentifier' => 'jobrouter-modulegroup',
        'labels' => 'LLL:EXT:' . Brotkrueml\JobRouterConnector\Extension::KEY . '/Resources/Private/Language/BackendModuleGroup.xlf',
    ]
);

// Register "Connections" module
TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
    'JobRouterConnector',
    'jobrouter',
    'connections',
    '',
    [
        Brotkrueml\JobRouterConnector\Controller\BackendController::class => 'list',
    ],
    [
        'access' => 'admin',
        'iconIdentifier' => 'jobrouter-module-connector',
        'labels' => Brotkrueml\JobRouterConnector\Extension::LANGUAGE_PATH_BACKEND_MODULE,
        'workspaces' => 'online',
    ]
);

// Add validation call for form field connection password
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][Brotkrueml\JobRouterConnector\Evaluation\Password::class] = '';
