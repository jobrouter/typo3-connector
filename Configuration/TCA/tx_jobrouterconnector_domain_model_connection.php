<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:jobrouter_connector/Resources/Private/Language/locallang_db.xlf:tx_jobrouterconnector_domain_model_connection',
        'label' => 'identifier',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'disabled',
        ],
        'rootLevel' => 1,
        'searchFields' => 'identifier,base_url,username',
        'iconfile' => 'EXT:jobrouter_connector/Resources/Public/Icons/tx_jobrouterconnector_domain_model_connection.svg'
    ],
    'interface' => [
        'showRecordFieldList' => 'disabled, identifier, base_url, username, password',
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

        'identifier' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobrouter_connector/Resources/Private/Language/locallang_db.xlf:tx_jobrouterconnector_domain_model_connection.identifier',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 30,
                'eval' => 'alphanum_x,required,trim,unique'
            ],
        ],
        'base_url' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobrouter_connector/Resources/Private/Language/locallang_db.xlf:tx_jobrouterconnector_domain_model_connection.base_url',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
                'eval' => 'nospace,required'
            ],
        ],
        'username' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobrouter_connector/Resources/Private/Language/locallang_db.xlf:tx_jobrouterconnector_domain_model_connection.username',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 50,
                'eval' => 'nospace,required'
            ],
        ],
        'password' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jobrouter_connector/Resources/Private/Language/locallang_db.xlf:tx_jobrouterconnector_domain_model_connection.password',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'nospace,password,required,' . \Brotkrueml\JobRouterConnector\Evaluation\Password::class
            ]
        ],
    ],
    'types' => [
        '1' => ['showitem' => '
            identifier, base_url, username, password,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            disabled,
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.extended,
        '],
    ],
];
