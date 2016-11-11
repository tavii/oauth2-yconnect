<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2016/11/11
 */

namespace Tavii\OAuth2\Client\Provider\Test;


use GuzzleHttp\ClientInterface;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tavii\OAuth2\Client\Provider\YConnect;

class YConnectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var YConnect
     */
    private $provider;

    public function setUp()
    {
        $this->provider = new YConnect([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none'
        ]);
    }

    public function testAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
        $this->assertArrayHasKey('state', $query);
        $this->assertArrayHasKey('scope', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('approval_prompt', $query);
        $this->assertNotNull($this->provider->getState());
    }

    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        $this->assertEquals('/yconnect/v1/authorization', $uri['path']);
    }

    public function testGetBaseAccessTokenUrl()
    {
        $params = [];
        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);
        $this->assertEquals('/yconnect/v1/token', $uri['path']);
    }

    public function testGetAccessToken()
    {
        $response = $this->prophesize(ResponseInterface::class);
        $response->getBody()
            ->willReturn('{"access_token":"mock_access_token","token_type":"bearer", "expires_in":"3600","refresh_token":"mock_refresh_token","id_token": "mock_id_token"}')
            ->shouldBeCalled()
            ;

        $response->getHeader(Argument::type('string'))
            ->willReturn(['content-type' => 'application/json'])
            ->shouldBeCalled()
            ;

        $response->getStatusCode()
            ->willReturn(200);

        $client = $this->prophesize(ClientInterface::class);
        $client->send(Argument::type(RequestInterface::class))
            ->willReturn($response->reveal())
            ->shouldBeCalledTimes(1);

        $this->provider->setHttpClient($client->reveal());
        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertNotNull($token->getExpires());
        $this->assertEquals('mock_refresh_token', $token->getRefreshToken());
    }

}