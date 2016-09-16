<?php

return [

    'oAuthProvider' => env('OAUTH_PROVIDER', ''),
    'oAuthId'       => env('OAUTH_ID', ''),
    'oAuthSecret'   => env('OAUTH_SECRET', ''),
    'oAuthRedirect' => env('OAUTH_REDIRECT', ''),
    'oAuthDomain'   => env('OAUTH_DOMAIN', ''),
    'oAuthAdmin'    => env('OAUTH_DELEGATED_ADMIN', ''),
    'credentials' 	=> env('OAUTH_CREDENTIALS_FILE', '')
];
