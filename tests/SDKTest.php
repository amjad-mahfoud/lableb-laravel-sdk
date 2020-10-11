<?php

// require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Amjad\Lableb\LablebSDK;

class SDKTest extends TestCase
{
    private function getSDK()
    {
        $sdk = new LablebSDK("wptest");
        $sdk->setToken("token");
        return $sdk;
    }

    public function testSearch()
    {
        $lableb = $this->getSDK();
        $params = [
            'q' => 'الذكاء',
            'cat' => 'Technology',
            'filter' => [
                'meta_sa' => ['Technology']
            ],
            'limit' => 10
        ];

        $response = $lableb->search('posts', $params);
        $this->assertTrue(is_array($response['results']));
    }
}
