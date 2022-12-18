<?php

use Brotkrueml\JobRouterConnector\Controller\ConnectionListController;
use Brotkrueml\JobRouterConnector\Evaluation\Password;
use Brotkrueml\JobRouterConnector\Extension;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

// Register "JobRouter" module group
ExtensionManagementUtility::addModule(
    'jobrouter',
    '',
    'before:tools',
    null,
    [
        'iconIdentifier' => 'jobrouter-modulegroup',
        'labels' => 'LLL:EXT:' . Extension::KEY . '/Resources/Private/Language/BackendModuleGroup.xlf',
    ]
);

// Add "Connections" module
ExtensionManagementUtility::addModule(
    'jobrouter',
    'connections',
    'top',
    '',
    [
        'routeTarget' => ConnectionListController::class . '::handleRequest',
        'access' => 'admin',
        'name' => Extension::MODULE_NAME,
        'iconIdentifier' => 'jobrouter-module-connector',
        'labels' => Extension::LANGUAGE_PATH_BACKEND_MODULE,
        'workspaces' => 'online',
    ]
);

// Add validation call for form field connection password
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][Password::class] = '';
