<?php

use JobRouter\AddOn\Typo3Connector\Evaluation\Password;
use JobRouter\AddOn\Typo3Connector\Hooks\DropObfuscatedPasswordInConnection;

defined('TYPO3') || die();

// Encrypt for form field connection password
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][Password::class] = '';
// Remove obfuscated password in connection record
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][]
    = DropObfuscatedPasswordInConnection::class;
