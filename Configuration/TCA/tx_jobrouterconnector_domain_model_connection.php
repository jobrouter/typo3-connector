<?php

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

return [
    'ctrl' => [
        'title' => \JobRouter\AddOn\Typo3Connector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection',
        'label' => 'name',
        'descriptionColumn' => 'description',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'disabled',
        ],
        'rootLevel' => 1,
        'searchFields' => 'name,handle,base_url,username,description',
        'iconfile' => 'EXT:' . \JobRouter\AddOn\Typo3Connector\Extension::KEY . '/Resources/Public/Icons/tx_jobrouterconnector_domain_model_connection.svg',
        'hideTable' => true,
    ],
    'columns' => [
        'disabled' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.enabled',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],

        'name' => [
            'label' => \JobRouter\AddOn\Typo3Connector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'handle' => [
            'label' => \JobRouter\AddOn\Typo3Connector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.handle',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 30,
                'eval' => 'alphanum_x,trim,unique',
                'required' => true,
            ],
        ],
        'base_url' => [
            'label' => \JobRouter\AddOn\Typo3Connector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.base_url',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim,nospace',
                'required' => true,
            ],
        ],
        'username' => [
            'label' => \JobRouter\AddOn\Typo3Connector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.username',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 50,
                'eval' => 'trim,nospace',
                'required' => true,
            ],
        ],
        'password' => [
            'label' => \JobRouter\AddOn\Typo3Connector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.password',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,nospace,' . \JobRouter\AddOn\Typo3Connector\Evaluation\Password::class,
                'required' => true,
            ],
        ],
        'timeout' => [
            'label' => \JobRouter\AddOn\Typo3Connector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.timeout',
            'config' => [
                'type' => 'number',
                'size' => 5,
                'range' => [
                    'lower' => 0,
                    'upper' => 240,
                ],
                'default' => 0,
                'slider' => [
                    'step' => 10,
                    'width' => 200,
                ],
            ],
        ],
        'verify' => [
            'label' => \JobRouter\AddOn\Typo3Connector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.verify',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                    ],
                ],
                'default' => 1,
            ],
        ],
        'proxy' => [
            'label' => \JobRouter\AddOn\Typo3Connector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.proxy',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'trim',
            ],
        ],
        'jobrouter_version' => [
            'label' => \JobRouter\AddOn\Typo3Connector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.jobrouter_version',
            'description' => \JobRouter\AddOn\Typo3Connector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.jobrouter_version.description',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'max' => 10,
                'readOnly' => true,
            ],
        ],
        'description' => [
            'label' => \JobRouter\AddOn\Typo3Connector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.description',
            'config' => [
                'type' => 'text',
                'rows' => 5,
                'cols' => 30,
            ],
        ],
    ],
    'types' => [
        '1' => [
            'showitem' => '
                --palette--;;system,
                --palette--;;credentials,
                --palette--;;options,
                --div--;' . \JobRouter\AddOn\Typo3Connector\Extension::LANGUAGE_PATH_DATABASE . ':tab.information,
                jobrouter_version,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                disabled,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                description,
            ',
        ],
    ],
    'palettes' => [
        'credentials' => [
            'label' => \JobRouter\AddOn\Typo3Connector\Extension::LANGUAGE_PATH_DATABASE . ':palette.credentials',
            'showitem' => 'username, password',
        ],
        'options' => [
            'label' => \JobRouter\AddOn\Typo3Connector\Extension::LANGUAGE_PATH_DATABASE . ':palette.options',
            'showitem' => 'timeout, verify, --linebreak--, proxy',
        ],
        'system' => [
            'label' => \JobRouter\AddOn\Typo3Connector\Extension::LANGUAGE_PATH_DATABASE . ':palette.system',
            'showitem' => 'name, handle, base_url',
        ],
    ],
];
