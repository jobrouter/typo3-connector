<?php

defined('TYPO3_MODE') || die('Access denied.');

(function () {
    $moduleContainerIcon = 'modulegroup-' . \Brotkrueml\JobRouterConnector\Extension::MODULE_GROUP;
    \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class)
        ->registerIcon(
            $moduleContainerIcon,
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:' . \Brotkrueml\JobRouterConnector\Extension::KEY . '/Resources/Public/Icons/modulegroup-jobrouter.svg']
        );

    // Place new module container before "tools"
    $position = array_search('tools', array_keys($GLOBALS['TBE_MODULES']));

    $GLOBALS['TBE_MODULES'] = array_slice($GLOBALS['TBE_MODULES'], 0, $position, true) +
        [\Brotkrueml\JobRouterConnector\Extension::MODULE_GROUP => ''] +
        array_slice($GLOBALS['TBE_MODULES'], $position, count($GLOBALS['TBE_MODULES']) - 1, true);

    $GLOBALS['TBE_MODULES']['_configuration'][\Brotkrueml\JobRouterConnector\Extension::MODULE_GROUP] = [
        'iconIdentifier' => $moduleContainerIcon,
        'labels' => 'LLL:EXT:' . \Brotkrueml\JobRouterConnector\Extension::KEY . '/Resources/Private/Language/BackendModuleContainer.xlf',
        'name' => \Brotkrueml\JobRouterConnector\Extension::MODULE_GROUP
    ];

    // Register new module
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'JobRouterConnector',
        'jobrouter',
        'jobrouterconnector',
        '',
        [
            \Brotkrueml\JobRouterConnector\Controller\BackendController::class => 'list',
        ],
        [
            'access' => 'admin',
            'icon' => 'EXT:' . \Brotkrueml\JobRouterConnector\Extension::KEY . '/Resources/Public/Icons/jobrouter-connector-module.svg',
            'labels' => \Brotkrueml\JobRouterConnector\Extension::LANGUAGE_PATH_BACKEND_MODULE,
        ]
    );

    // Add validation call for form field connection password
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\Brotkrueml\JobRouterConnector\Evaluation\Password::class] = '';
})();
