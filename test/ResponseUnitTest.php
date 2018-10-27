<?php
require_once('Request.php');
require_once('Response.php');
require_once('ParameterBag.php');
require_once('ServerBag.php');
require_once('FileBag.php');
require_once('HeaderBag.php');
require_once('ResponseCookie.php');
require_once('ResponseHeaderBag.php');
require_once('Cookie.php');
require_once 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
class ResponseTest extends TestCase
{
    public function testResponse()
    {
        $response = new Response();
        $response->setStatus('300');
        $this->assertEquals('300', $response->getStatusCode(), '->get status Code');

        $response = new Response();
        $response->headers->set('cache-control', 'private');
        $response->headers->set('Last-Modified', 'Sun, 25 Aug 2013 18:33:31 GMT');
        $response->headers->set('date', 'Sun, 25 Aug 2013 18:33:31 GMT');

        $this->assertEquals('private', $response->headers->get('cache-control'), '->get header by name');

        $this->assertEquals(array(
            'cache-control'=>array('private'),
            'last-modified'=>array('Sun, 25 Aug 2013 18:33:31 GMT'),
            'date'=>array('Sun, 25 Aug 2013 18:33:31 GMT')), $response->headers->all(), '->get header all');



        $response = new Response();
        $response->setContent('New content');
        $this->assertEquals((string) 'New content', $response->getContent(), '->get content');

        $response = new Response();
        $cookies = new Cookie('name1', 'newcookie');
        $response->headers->setCookie($cookies);

        $this->assertEquals('newcookie',$response->headers->getCookiName('name1'), '->get cookie by name');

        $this->assertEquals(['name1' =>
            (object) new Cookie('name1', 'newcookie')], $response->headers->getCookies(), '->get set cookies array');

    }
}