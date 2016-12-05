<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2016/12/06
 */

namespace Tavii\OAuth2\Client\Provider\Test;


use Tavii\OAuth2\Client\Provider\YConnectResourceOwner;

class YConnectResourceOwnerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function notExistGetterMethodCalled()
    {
        $resource = new YConnectResourceOwner(['given_name' => 'polidog']);
        $actual = $resource->getGivenName();
        $this->assertEquals('polidog', $actual);
    }

    /**
     * @test
     */
    public function notExistUserId()
    {
        $resource = new YConnectResourceOwner([]);
        $this->assertNull($resource->getId());
    }
}