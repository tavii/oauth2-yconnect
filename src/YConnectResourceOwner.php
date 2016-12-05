<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2016/11/09
 */

namespace Tavii\OAuth2\Client\Provider;


use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class YConnectResourceOwner implements ResourceOwnerInterface
{
    /**
     * @var array
     */
    protected $response;

    /**
     * YConnectResourceOwner constructor.
     * @param array $response
     */
    public function __construct(array $response = [])
    {
        $this->response = $response;
    }

    public function __call($name, $arguments)
    {
        $get = substr($name,0, 3);
        if ($get !== 'get') {
            return null;
        }
        $parameter = function($name) {
            return ltrim(strtolower(preg_replace('/[A-Z]/', '_\0', str_replace("get", "", $name))), '_');
        };
        return $this->getResource($parameter($name));
    }


    public function getId()
    {
        return $this->getResource('user_id');
    }

    public function getName()
    {
        return $this->getResource('name');
    }

    public function getEmail()
    {
        return $this->getResource('email');
    }

    public function getAddress()
    {
        return $this->getResource('address');
    }

    public function toArray()
    {
        return $this->response;
    }

    protected function getResource($name)
    {
        return isset($this->response[$name]) ? $this->response[$name] : null;
    }

}