<?php

declare(strict_types=1);

use JobRouter\AddOn\RestClient\Client\RestClient;
use JobRouter\AddOn\Typo3Connector\Evaluation\Password;
use JobRouter\AddOn\Typo3Connector\Extension;
use JobRouter\AddOn\Typo3Connector\Hooks\DropObfuscatedPasswordInConnection;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

// Encrypt for form field connection password
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][Password::class] = '';
// Remove obfuscated password in connection record
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][]
    = DropObfuscatedPasswordInConnection::class;


ExtensionManagementUtility::addTypoScriptSetup(
    '@import "EXT:' . Extension::KEY . '/Configuration/TypoScript/"'
);

if (! Environment::isComposerMode() && ! class_exists(RestClient::class)) {
    @include 'phar://' . ExtensionManagementUtility::extPath(Extension::KEY) . 'Resources/Private/PHP/jobrouter-client.phar/vendor/autoload.php';
}
