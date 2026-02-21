<?php

/**
 * OAuth 2.0 Configuration
 */

$serverName = $_SERVER['SERVER_NAME'] ?? 'localhost';
$isDev = ($serverName == 'localhost' || $serverName == '127.0.0.1' || strpos($serverName, '192.168.') === 0);

// OAuth Provider Settings
define('OAUTH_PROVIDERS', [
    'google' => [
        'name' => 'Google',
        'client_id' => $isDev ? '757873673410-o1e16g7826g84t2rhrqs525kn4mkq858.apps.googleusercontent.com' : 'YOUR_PROD_GOOGLE_CLIENT_ID',
        'client_secret' => $isDev ? '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' : 'YOUR_PROD_GOOGLE_CLIENT_SECRET',
        'redirect_uri' => ROOT . '/auth/callback/google',
        'auth_url' => 'https://accounts.google.com/o/oauth2/v2/auth',
        'token_url' => 'https://oauth2.googleapis.com/token',
        'user_info_url' => 'https://www.googleapis.com/oauth2/v2/userinfo',
        'scopes' => ['openid', 'email', 'profile'],
        'issuer' => 'https://accounts.google.com'
    ],
    'facebook' => [
        'name' => 'Facebook',
        'client_id' => $isDev ? 'YOUR_FACEBOOK_APP_ID' : 'YOUR_PROD_FACEBOOK_APP_ID',
        'client_secret' => $isDev ? 'YOUR_FACEBOOK_APP_SECRET' : 'YOUR_PROD_FACEBOOK_APP_SECRET',
        'redirect_uri' => ROOT . '/auth/callback/facebook',
        'auth_url' => 'https://www.facebook.com/v18.0/dialog/oauth',
        'token_url' => 'https://graph.facebook.com/v18.0/oauth/access_token',
        'user_info_url' => 'https://graph.facebook.com/me?fields=id,name,email',
        'scopes' => ['email', 'public_profile'],
        'issuer' => 'https://www.facebook.com'
    ]
]);

// Token Lifetimes
define('ACCESS_TOKEN_LIFETIME', 900);        // 15 minutes (JWT access token)
define('REFRESH_TOKEN_LIFETIME', 2592000);  // 30 days (JWT refresh token)
define('OAUTH_STATE_LIFETIME', 600);        // 10 minutes
