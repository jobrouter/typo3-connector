<?php

defined('TYPO3') || die();

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
    '@import "EXT:' . JobRouter\AddOn\Typo3Connector\Extension::KEY . '/Configuration/TypoScript/"'
);

if (! TYPO3\CMS\Core\Core\Environment::isComposerMode() && ! class_exists(JobRouter\AddOn\RestClient\Client\RestClient::class)) {
    @include 'phar://' . TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(JobRouter\AddOn\Typo3Connector\Extension::KEY) . 'Resources/Private/PHP/jobrouter-client.phar/vendor/autoload.php';
}
