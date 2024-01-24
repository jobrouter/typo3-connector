<?php

defined('TYPO3') || die();

TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
    '@import "EXT:' . Brotkrueml\JobRouterConnector\Extension::KEY . '/Configuration/TypoScript/"'
);

if (! TYPO3\CMS\Core\Core\Environment::isComposerMode() && ! class_exists(JobRouter\AddOn\RestClient\Client\RestClient::class)) {
    @include 'phar://' . TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath(Brotkrueml\JobRouterConnector\Extension::KEY) . 'Resources/Private/PHP/jobrouter-client.phar/vendor/autoload.php';
}
