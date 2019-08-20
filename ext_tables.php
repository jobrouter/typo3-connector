<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extensionKey) {
        if (TYPO3_MODE === 'BE') {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'Brotkrueml.JobRouterConnector',
                'tools',
                'tx_jobrouter_connector',
                '',
                [
                    'Backend' => 'list',
                ],
                [
                    'access' => 'index',
                    'icon' => 'EXT:' . $extensionKey . '/Resources/Public/Icons/Extension.svg',
                    'labels' => 'LLL:EXT:' . $extensionKey . '/Resources/Private/Language/locallang_module.xlf',
                ]
            );

            // Add validation call for form field connection password
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\Brotkrueml\JobRouterConnector\Evaluation\Password::class] = '';
        }
    },
    'jobrouter_connector'
);
