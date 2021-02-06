<?php

defined('TYPO3') || die();

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
    '@import "EXT:' . Brotkrueml\JobRouterConnector\Extension::KEY . '/Configuration/TypoScript/"'
);
