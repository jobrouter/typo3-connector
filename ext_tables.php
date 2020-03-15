<?php
defined('TYPO3_MODE') || die('Access denied.');

(function ($moduleContainerKey = 'jobrouter', $extensionKey = 'jobrouter_connector') {
    $moduleContainerIcon = 'module-' . $moduleContainerKey;
    \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class)
        ->registerIcon(
            $moduleContainerIcon,
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:' . $extensionKey . '/Resources/Public/Icons/jobrouter-module-container.svg']
        );

    // Place new module container before "tools"
    $position = array_search('tools', array_keys($GLOBALS['TBE_MODULES']));

    $GLOBALS['TBE_MODULES'] = array_slice($GLOBALS['TBE_MODULES'], 0, $position, true) +
        [$moduleContainerKey => ''] +
        array_slice($GLOBALS['TBE_MODULES'], $position, count($GLOBALS['TBE_MODULES']) - 1, true);

    $GLOBALS['TBE_MODULES']['_configuration'][$moduleContainerKey] = [
        'iconIdentifier' => $moduleContainerIcon,
        'labels' => 'LLL:EXT:' . $extensionKey . '/Resources/Private/Language/BackendModuleContainer.xlf',
        'name' => $moduleContainerKey
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
            'icon' => 'EXT:' . $extensionKey . '/Resources/Public/Icons/jobrouter-connector-module.svg',
            'labels' => 'LLL:EXT:' . $extensionKey . '/Resources/Private/Language/BackendModule.xlf',
        ]
    );

    // Add validation call for form field connection password
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\Brotkrueml\JobRouterConnector\Evaluation\Password::class] = '';
})();
