<?php
// tests/Controller/JobControllerTest.php
namespace App\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JobControllerTest extends WebTestCase
{
    /**
     * @dataProvider provideUrls
     */
    public function testPageIsSuccessful($url)
    {
        $client = static::createClient();

        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isSuccessful());

        //$this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    public function provideUrls()
    {
        return [
            ['/'],
            ['/login'],
            ['/register'],
            ['/register'],
            ['/register'],
            ['/affiliates'],
            ['/affiliate/create']
        ];
    }
    public function testListJobsAjax()
    {
        $client = static::createClient();
        // the required HTTP_X_REQUESTED_WITH header is added automatically
        $client->xmlHttpRequest('GET', '/paginate', ['page' =>1 ,'cat'=>71]);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}