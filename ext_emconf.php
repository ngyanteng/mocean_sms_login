<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'MoceanSMS Login',
    'description' => '2FA SMS Login.',
    'category' => 'plugin',
    'author' => 'moceanapi',
    'author_company' => 'Micro Ocean Technologies',
    'author_email' => 'plugin@moceanapi.com',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4-11.0.0',
        ]
    ],
    'autoload' => [
        'psr-4' => [
            'Mocean\\MoceanSmsLogin\\' => 'Classes'
        ]
    ],
];
