<?php

return [
    'frontend' => [
        'mocean/mocean-sms-login/force-logout' => [
            'target' => \Mocean\MoceanSmsLogin\Middleware\ForceLogout::class,
            'after' => [
                'typo3/cms-frontend/page-resolver',
            ],
        ]
    ]
];
