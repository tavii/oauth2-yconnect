# YConnect(Yahoo Japan) Provider for OAuth 2.0 Client

[![License](https://img.shields.io/packagist/l/league/oauth2-google.svg)](https://github.com/tavii/oauth2-yconnect/blob/master/LICENSE)
[![Build Status](https://travis-ci.org/tavii/oauth2-yconnect.svg?branch=master)](https://travis-ci.org/tavii/oauth2-yconnect)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tavii/oauth2-yconnect/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tavii/oauth2-yconnect/?branch=master)

This package provides YConnect(Yahoo Japan) OAuth2.0 support for the PHP League's [ OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

# Installation

To install, use composer.

```
$ composer require tavii/oauth2-yconnect
```

Usage is the same as The League's OAuth client, using \Tavii\OAuth2\Client\Provider\YConnect as the provider.


```
$provider = new Tavii\OAuth2\Client\Provider\YConnect([
    'clientId'          => '{yconnect-client-id}',
    'clientSecret'      => '{yconnect-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url',
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $user = $provider->getResourceOwner($token);

        // Use these details to create a new profile
        printf('Hello %s!', $user->getNamex());

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    echo $token->getToken();
}
```

