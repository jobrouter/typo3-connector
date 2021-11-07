<?php

defined('TYPO3') || die();

(static function () {
    if ((new TYPO3\CMS\Core\Information\Typo3Version())->getMajorVersion() === 10) {
        // Since TYPO3 v11.4 icons can be registered in Configuration/Icons.php
        /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
        $iconRegistry = TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $iconRegistry->registerIcon(
            'jobrouter-modulegroup',
            TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            [
                'source' => 'EXT:' . Brotkrueml\JobRouterConnector\Extension::KEY . '/Resources/Public/Icons/modulegroup-jobrouter.svg',
            ]
        );
        $iconRegistry->registerIcon(
            'jobrouter-module-connector',
            TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            [
                'source' => 'EXT:' . Brotkrueml\JobRouterConnector\Extension::KEY . '/Resources/Public/Icons/module-connector.svg',
            ]
        );
    }

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
})();
