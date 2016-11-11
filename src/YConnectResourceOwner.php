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


    public function getId()
    {
        return $this->response['user_id'] ?: null;
    }

    public function getName()
    {
        return $this->response['name'] ?: null;
    }

    public function getEmail()
    {
        return $this->response['email'] ?: null;
    }



    public function toArray()
    {
        return $this->response;
    }

}