<?php

$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => '8r45A5yonwetU69MZHztLvPe5rn64KUq',    // The client ID assigned to you by the provider
    'clientSecret'            => ' SVDVSONzBAONJJpDaKU0PB0IZwwogxOB ',   // The client password assigned to you by the provider
    'redirectUri'             => 'http://18.188.41.55/',
    'urlAuthorize'            => 'https://api.contaazul.com/auth/authorize?redirect_uri={REDIRECT_URI}&client_id={CLIENT_ID}&scope=sales&state={STATE}',
    'urlAccessToken'          => 'https://api.contaazul.com/oauth2/token?grant_type=authorization_code&redirect_uri={REDIRECT_URI}&code={CODE}',
    'urlResourceOwnerDetails' => 'https://api.contaazul.com/oauth2/token?grant_type=refresh_token&refresh_token={REFRESH_TOKEN}'
]);

// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {

    // Fetch the authorization URL from the provider; this returns the
    // urlAuthorize option and generates and applies any necessary parameters
    // (e.g. state).
    $authorizationUrl = $provider->getAuthorizationUrl();

    // Get the state generated for you and store it to the session.
    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL.
    header('Location: ' . $authorizationUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    try {

        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        echo $accessToken->getToken() . "\n";
        echo $accessToken->getRefreshToken() . "\n";
        echo $accessToken->getExpires() . "\n";
        echo ($accessToken->hasExpired() ? 'expired' : 'not expired') . "\n";

        // Using the access token, we may look up details about the
        // resource owner.
        $resourceOwner = $provider->getResourceOwner($accessToken);

        var_export($resourceOwner->toArray());

        // The provider provides a way to get an authenticated API request for
        // the service, using the access token; it returns an object conforming
        // to Psr\Http\Message\RequestInterface.
        $request = $provider->getAuthenticatedRequest(
            'GET',
            'http://brentertainment.com/oauth2/lockdin/resource',
            $accessToken
        );

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        exit($e->getMessage());

    }

    $provider = new \League\OAuth2\Client\Provider\GenericProvider([
        'clientId'                => '8r45A5yonwetU69MZHztLvPe5rn64KUq',    // The client ID assigned to you by the provider
        'clientSecret'            => ' SVDVSONzBAONJJpDaKU0PB0IZwwogxOB ',   // The client password assigned to you by the provider
        'redirectUri'             => 'http://18.188.41.55/',
        'urlAuthorize'            => 'https://api.contaazul.com/auth/authorize?redirect_uri={REDIRECT_URI}&client_id={CLIENT_ID}&scope=sales&state={STATE}',
        'urlAccessToken'          => 'https://api.contaazul.com/oauth2/token?grant_type=authorization_code&redirect_uri={REDIRECT_URI}&code={CODE}',
        'urlResourceOwnerDetails' => 'https://api.contaazul.com/oauth2/token?grant_type=refresh_token&refresh_token={REFRESH_TOKEN}'
    ]);
    
    $existingAccessToken = getAccessTokenFromYourDataStore();
    
    if ($existingAccessToken->hasExpired()) {
        $newAccessToken = $provider->getAccessToken('refresh_token', [
            'refresh_token' => $existingAccessToken->getRefreshToken()
        ]);
    
        // Purge old access token and store new access token to your data store.
    }

}
