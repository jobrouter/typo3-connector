<?php

use Brotkrueml\JobRouterConnector\Controller\ConnectionListController;
use Brotkrueml\JobRouterConnector\Evaluation\Password;
use Brotkrueml\JobRouterConnector\Extension;
use Brotkrueml\JobRouterConnector\Hooks\DropObfuscatedPasswordInConnection;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

if ((new Typo3Version())->getMajorVersion() === 11) {
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
}

// Encrypt for form field connection password
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][Password::class] = '';
// Remove obfuscated password in connection record
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][]
    = DropObfuscatedPasswordInConnection::class;
