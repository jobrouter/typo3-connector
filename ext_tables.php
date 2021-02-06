<?php

defined('TYPO3') || die();

(function () {
    // Register "JobRouter" module group
    TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
        'jobrouter',
        '',
        'before:tools',
        null,
        [
            'icon' => 'EXT:' . Brotkrueml\JobRouterConnector\Extension::KEY . '/Resources/Public/Icons/modulegroup-jobrouter.svg',
            'labels' => 'LLL:EXT:' . Brotkrueml\JobRouterConnector\Extension::KEY . '/Resources/Private/Language/BackendModuleGroup.xlf',
        ]
    );

    // Register "Connections" module
    TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'JobRouterConnector',
        'jobrouter',
        'jobrouterconnector',
        '',
        [
            Brotkrueml\JobRouterConnector\Controller\BackendController::class => 'list',
        ],
        [
            'access' => 'admin',
            'icon' => 'EXT:' . Brotkrueml\JobRouterConnector\Extension::KEY . '/Resources/Public/Icons/module-connector.svg',
            'labels' => Brotkrueml\JobRouterConnector\Extension::LANGUAGE_PATH_BACKEND_MODULE,
        ]
    );

    // Add validation call for form field connection password
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][Brotkrueml\JobRouterConnector\Evaluation\Password::class] = '';
})();
