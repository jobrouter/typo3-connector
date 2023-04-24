<?php

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

defined('TYPO3') || die();

if ((new TYPO3\CMS\Core\Information\Typo3Version())->getMajorVersion() < 12) {
    $GLOBALS['TCA']['tx_jobrouterconnector_domain_model_connection']['columns']['timeout']['config'] = array_merge(
        $GLOBALS['TCA']['tx_jobrouterconnector_domain_model_connection']['columns']['timeout']['config'],
        [
            'type' => 'input',
            'eval' => 'int',
        ],
    );

    $GLOBALS['TCA']['tx_jobrouterconnector_domain_model_connection']['columns']['disabled']['config']['items'] = [
        [
            0 => '',
            1 => '',
            'invertStateDisplay' => true,
        ],
    ];

    $GLOBALS['TCA']['tx_jobrouterconnector_domain_model_connection']['columns']['timeout']['config']['items'] = [
        [
            0 => '',
            1 => '',
        ],
    ];
}
