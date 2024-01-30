<?php

use JobRouter\AddOn\RestClient\Client\RestClient;
use JobRouter\AddOn\Typo3Connector\Extension;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

ExtensionManagementUtility::addTypoScriptSetup(
    '@import "EXT:' . Extension::KEY . '/Configuration/TypoScript/"'
);

if (! Environment::isComposerMode() && ! class_exists(RestClient::class)) {
    @include 'phar://' . ExtensionManagementUtility::extPath(Extension::KEY) . 'Resources/Private/PHP/jobrouter-client.phar/vendor/autoload.php';
}
