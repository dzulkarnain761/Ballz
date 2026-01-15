<?php

class Auth extends Controller
{
    private $userModel;
    private $oauthModel;
    private $sessionModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->oauthModel = new OAuthModel();
        $this->sessionModel = new AuthSessionModel();
    }

    public function index()
    {
        if (isAuthenticated()) {
            redirect('dashboard');
        }
        $this->view("login");
    }

    public function login()
    {
        if (isAuthenticated()) {
            redirect('dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && checkCRFT()) {
            $_SESSION['error'] = 'Please use social login (Google or Facebook)';
            redirect('auth/login');
        }
        $this->view("login");
    }

    public function google()
    {
        $authUrl = buildOAuthUrl('google');
        if (!$authUrl) {
            $_SESSION['error'] = 'Google OAuth is not configured';
            redirect('auth/login');
        }
        header('Location: ' . $authUrl);
        exit;
    }

    public function facebook()
    {
        $authUrl = buildOAuthUrl('facebook');
        if (!$authUrl) {
            $_SESSION['error'] = 'Facebook OAuth is not configured';
            redirect('auth/login');
        }
        header('Location: ' . $authUrl);
        exit;
    }

    public function callback($provider)
    {
        if (!isset(OAUTH_PROVIDERS[$provider])) {
            $_SESSION['error'] = 'Invalid OAuth provider';
            redirect('auth/login');
        }

        $code = $_GET['code'] ?? null;
        $state = $_GET['state'] ?? null;
        $error = $_GET['error'] ?? null;

        if ($error) {
            $_SESSION['error'] = 'OAuth failed: ' . $error;
            redirect('auth/login');
        }

        if (!$state || !verifyOAuthState($state)) {
            $_SESSION['error'] = 'Invalid OAuth state. Please try again.';
            redirect('auth/login');
        }

        if (!$code) {
            $_SESSION['error'] = 'No authorization code received';
            redirect('auth/login');
        }

        // Exchange code for tokens
        $tokenResponse = exchangeOAuthCode($provider, $code);
        if (!$tokenResponse['success']) {
            $_SESSION['error'] = 'Failed to obtain access token';
            redirect('auth/login');
        }

        // Get user info
        $userInfoResponse = getOAuthUserInfo($provider, $tokenResponse['access_token']);
        if (!$userInfoResponse['success']) {
            $_SESSION['error'] = 'Failed to get user information';
            redirect('auth/login');
        }

        $oauthUser = $userInfoResponse['user'];

        // Get or create provider record
        $providerRecord = $this->oauthModel->getOrCreateProvider(
            $provider,
            OAUTH_PROVIDERS[$provider]['issuer']
        );

        // Check if OAuth account exists
        $oauthAccount = $this->oauthModel->getAccountByProvider(
            $providerRecord['id'],
            $oauthUser['provider_user_id']
        );

        if ($oauthAccount) {
            // Existing user - update tokens
            $this->oauthModel->updateAccountTokens(
                $oauthAccount['id'],
                $tokenResponse['access_token'],
                $tokenResponse['refresh_token'] ?? null,
                isset($tokenResponse['expires_in']) 
                    ? date('Y-m-d H:i:s', time() + $tokenResponse['expires_in']) 
                    : null
            );
            $user = $this->userModel->getOne($oauthAccount['user_id']);
        } else {
            // New user
            $user = $this->createUserFromOAuth($oauthUser, $providerRecord, $tokenResponse);
            if (!$user) {
                $_SESSION['error'] = 'Failed to create user account';
                redirect('auth/login');
            }
        }

        // Create session
        $accessToken = generateAccessToken();
        $refreshToken = generateRefreshToken();
        $expiresAt = date('Y-m-d H:i:s', time() + ACCESS_TOKEN_LIFETIME);

        $sessionId = $this->sessionModel->createSession($user['id'], $accessToken, $refreshToken, $expiresAt);

        if (!$sessionId) {
            $_SESSION['error'] = 'Failed to create session';
            redirect('auth/login');
        }

        setAuthSession($user, $accessToken, $refreshToken);
        $_SESSION['success'] = 'Welcome, ' . $user['name'] . '!';
        redirect('dashboard');
    }

    private function createUserFromOAuth($oauthUser, $provider, $tokenResponse)
    {
        // Check if user with email exists
        $existingUser = $this->userModel->getByEmail($oauthUser['email']);
        
        if ($existingUser) {
            $userId = $existingUser['id'];
        } else {
            $userId = $this->userModel->createFromOAuth([
                'name' => $oauthUser['name'] ?? 'User',
                'email' => $oauthUser['email'],
                'phone' => null,
                'reward_points' => 0,
                'status' => 'active'
            ]);
        }

        if (!$userId) {
            return null;
        }

        // Link OAuth account
        $this->oauthModel->createAccount([
            'user_id' => $userId,
            'provider_id' => $provider['id'],
            'provider_user_id' => $oauthUser['provider_user_id'],
            'access_token' => $tokenResponse['access_token'],
            'refresh_token' => $tokenResponse['refresh_token'] ?? null,
            'token_expires_at' => isset($tokenResponse['expires_in']) 
                ? date('Y-m-d H:i:s', time() + $tokenResponse['expires_in']) 
                : null
        ]);

        return $this->userModel->getOne($userId);
    }

    public function register()
    {
        redirect('auth/login');
    }

    public function logout()
    {
        clearAuthSession();
        session_destroy();
        $_SESSION['success'] = 'You have been logged out';
        redirect("auth/login");
    }
}
