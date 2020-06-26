<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:mocean_sms_login/Resources/Private/Language/locallang_db.xlf:tx_moceansmslogin_domain_model_user',
        'label' => 'username',
        'iconfile' => 'EXT:mocean_sms_login/Resources/Public/Icons/Extension.svg'
    ],
    'columns' => [
        'username' => [
            'label' => 'LLL:EXT:mocean_sms_login/Resources/Private/Language/locallang_db.xlf:tx_moceansmslogin_domain_model_user.username',
            'exclude' => 1,
			'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim, required'
            ]
        ],
        'telephone' => [
            'label' => 'LLL:EXT:mocean_sms_login/Resources/Private/Language/locallang_db.xlf:tx_moceansmslogin_domain_model_user.telephone',
            'exclude' => 1,
			'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'trim, required'
            ]
        ],
        'enabled' => [
            'label' => 'LLL:EXT:mocean_sms_login/Resources/Private/Language/locallang_db.xlf:tx_moceansmslogin_domain_model_user.enabled',
            'exclude' => 1,
			'config' => [
                'type' => 'check',
				'default' => '0'
            ]
        ],
    ],
    'types' => [
        '0' => ['showitem' => 'username, telephone, enabled']
    ]
];
