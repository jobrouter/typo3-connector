<?php

/*
 * This file is part of the "jobrouter_connector" extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

return [
    'ctrl' => [
        'title' => \Brotkrueml\JobRouterConnector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection',
        'label' => 'name',
        'descriptionColumn' => 'description',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'disabled',
        ],
        'rootLevel' => 1,
        'searchFields' => 'name,handle,base_url,username,description',
        'iconfile' => 'EXT:' . \Brotkrueml\JobRouterConnector\Extension::KEY . '/Resources/Public/Icons/tx_jobrouterconnector_domain_model_connection.svg',
        'hideTable' => true,
    ],
    'columns' => [
        'disabled' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.enabled',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ]
        ],

        'name' => [
            'exclude' => true,
            'label' => \Brotkrueml\JobRouterConnector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'required,trim'
            ],
        ],
        'handle' => [
            'exclude' => true,
            'label' => \Brotkrueml\JobRouterConnector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.handle',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 30,
                'eval' => 'alphanum_x,required,trim,unique'
            ],
        ],
        'base_url' => [
            'exclude' => true,
            'label' => \Brotkrueml\JobRouterConnector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.base_url',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'nospace,required'
            ],
        ],
        'username' => [
            'exclude' => true,
            'label' => \Brotkrueml\JobRouterConnector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.username',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'nospace,required'
            ],
        ],
        'password' => [
            'exclude' => true,
            'label' => \Brotkrueml\JobRouterConnector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.password',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'nospace,password,required,' . \Brotkrueml\JobRouterConnector\Evaluation\Password::class
            ]
        ],
        'jobrouter_version' => [
            'exclude' => true,
            'label' => \Brotkrueml\JobRouterConnector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.jobrouter_version',
            'description' => \Brotkrueml\JobRouterConnector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.jobrouter_version.description',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'max' => 10,
                'readOnly' => true,
            ],
        ],
        'description' => [
            'exclude' => true,
            'label' => \Brotkrueml\JobRouterConnector\Extension::LANGUAGE_PATH_DATABASE . ':tx_jobrouterconnector_domain_model_connection.description',
            'config' => [
                'type' => 'text',
                'rows' => 5,
                'cols' => 30
            ]
        ],
    ],
    'types' => [
        '1' => ['showitem' => '
            name, handle, base_url,
            --palette--;;credentials,
            --div--;' . \Brotkrueml\JobRouterConnector\Extension::LANGUAGE_PATH_DATABASE . ':tab.information,
            jobrouter_version,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            disabled,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            description,
        '],
    ],
    'palettes' => [
        'credentials' => [
            'label' => \Brotkrueml\JobRouterConnector\Extension::LANGUAGE_PATH_DATABASE . ':palette.credentials',
            'showitem' => 'username, password',
        ],
    ],
];
