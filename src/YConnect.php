<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2016/11/09
 */

namespace Tavii\OAuth2\Client\Provider;


use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use Tavii\OAuth2\Client\Provider\Exception\YConnectIdentityProviderException;

class YConnect extends AbstractProvider
{
    const API_DOMAIN = 'https://auth.login.yahoo.co.jp/';

    const USERINFO_DOMAIN = 'https://userinfo.yahooapis.jp';

    public $version = 'v1';

    /**
     * {@inheritdoc}
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->getApiBaseUrl().'/authorization';
    }

    protected function getAuthorizationHeaders($token = null)
    {
        if ($token != null) {
            return ['Authorization' => 'Bearer '.$token];
        }
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->getApiBaseUrl().'/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getAccessTokenOptions(array $params)
    {
        $options = parent::getAccessTokenOptions([
            'code' => $params['code'],
            'grant_type' => 'authorization_code',
            'redirect_uri' => $params['redirect_uri'],
        ]);

        $options['headers']['Authorization'] = 'Basic '.base64_encode($params['client_id']. ':' . $params['client_secret']);
        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->getUserInfoBaseUrl().'/attribute?schema=openid';
    }


    public function getAuthenticatedRequest($method, $url, $token, array $options = [])
    {
        return $this->createRequest($method, $url, $token, $options);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultScopes()
    {
        return [
            'openid',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            throw YConnectIdentityProviderException::clientException($response, $data);
        } elseif (isset($data['error'])) {
            throw YConnectIdentityProviderException::oauthException($response, $data);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new YConnectResourceOwner($response);
    }

    protected function getApiBaseUrl()
    {
        return static::API_DOMAIN. "/yconnect/".$this->version;
    }

    protected function getUserInfoBaseUrl()
    {
        return static::USERINFO_DOMAIN. '/yconnect/'.$this->version;
    }
}