<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2016/11/09
 */

namespace Tavii\OAuth2\Client\Provider\Exception;


use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Psr\Http\Message\ResponseInterface;

class YConnectIdentityProviderException extends IdentityProviderException
{
    /**
     * Creates client exception from response.
     *
     * @param ResponseInterface $response
     * @param array             $data
     *
     * @return YConnectIdentityProviderException
     */
    public static function clientException(ResponseInterface $response, array $data)
    {
        var_dump($data);
        return static::fromResponse(
            $response,
            isset($data['error']) ? $data['error'] : $response->getReasonPhrase()
        );
    }

    /**
     * Creates oauth exception from response.
     *
     * @param ResponseInterface $response
     * @param array             $data
     *
     * @return YConnectIdentityProviderException
     */
    public static function oauthException(ResponseInterface $response, array $data)
    {
        return static::fromResponse(
            $response,
            isset($data['error']) ? $data['error'] : $response->getReasonPhrase()
        );
    }

    /**
     * Creates identity exception from response.
     *
     * @param ResponseInterface $response
     * @param null              $message
     *
     * @return static
     */
    protected static function fromResponse(ResponseInterface $response, $message = null)
    {
        return new static($message, $response->getStatusCode(), (string) $response->getBody());
    }
}