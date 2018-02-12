<?php
/**
 * Created by PhpStorm.
 * User: nacer
 * Date: 09/02/18
 * Time: 23:51
 */

namespace AppBundle\Test;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class RateTestCase extends TestCase
{
    private static $staticClient;

    /**
     * @vat Client
     */
    protected $client;

    public static function setUpBeforeClass()
    {
        self::$staticClient= new Client([
           'base_url' => 'http://localhost:8000',
            'defaults'=> [
                'exceptions' => false,
            ]
        ]);
    }

    public function setup(){
        $this->client = self::$staticClient;
    }
}