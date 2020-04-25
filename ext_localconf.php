<?php
defined('TYPO3_MODE') || die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
    '@import "EXT:' . \Brotkrueml\JobRouterConnector\Extension::KEY . '/Configuration/TypoScript/"'
);
