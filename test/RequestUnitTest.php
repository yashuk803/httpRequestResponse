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
class RequestTest extends TestCase
{

    public function testRequest()
    {

        $request = new Request(array('foo' => 'bar'), [], [], [], [], []);
        $this->assertEquals('GET', $request->getMethod(), '->get method GET');

        $request = new Request([], array('foo' => 'bar'), [], [], [], []);
        $request->setMethod('POST');
        $this->assertEquals('POST', $request->getMethod(), '->get method POST');

        $request = new Request([], [], [], [], [], []);
        $this->assertEquals('GET', $request->getMethod(), '->get method GET');

        $request = new Request();
        $request->headers->set('X-HTTP-METHOD-OVERRIDE', 'delete');
        $request->headers->set('CONTENT_MD5', 'md5');
        $this->assertEquals(array('x-http-method-override'=>array('delete'), 'content-md5' => array('md5')), $request->headers->all(), '->get all set headers ');
        $this->assertEquals('md5', $request->headers->get('CONTENT_MD5'), '->get all set headers ');



        $request = new Request([],[],[],[],array(
            'HTTP_HOST' => 'example.com',
            'HTTPS' => 'on',
            'SERVER_PORT' => 443,
            'PHP_AUTH_USER' => 'fabien',
            'PHP_AUTH_PW' => 'pa$$',
            'QUERY_STRING' => 'foo=bar',
            'CONTENT_TYPE' => 'application/json',
            'CONTENT_LENGTH' => 'ru',
            'CONTENT_MD5' => 'md5',
        ),[]);
        $this->assertEquals('md5', $request->headers->get('CONTENT_MD5'), '->Returns a header value by name');
        $this->assertEquals(array('host'=> array('example.com'),
            'content-type' =>  array('application/json'),
            'content-length' => array('ru'),
            'content-md5' => array('md5')), $request->headers->all(),'->Returns all header');



        $request = new Request([],[],[
            'name'=>'newvalue',
            'expires'=>'date',
            'path'=>'/',
            'domain'=>'example.org'
        ],[],[],[]);



        $this->assertEquals([
            'name'=>'newvalue',
            'expires'=>'date',
            'path'=>'/',
            'domain'=>'example.org'
        ], $request->cookies->all(),'->Returns all cookies');
        $this->assertEquals('newvalue', $request->cookies->get('name'),'->Returns a cookies value by name ');


        $request = new Request();
        $request->cookies->set('name', 'newvalue');
        $request->cookies->set('domain', 'example.org');
        $this->assertEquals('newvalue', $request->cookies->get('name'),'->Returns a cookies value by name ');
        $this->assertEquals([
            'name'=>'newvalue',
            'domain'=>'example.org'
        ], $request->cookies->all(),'->Returns all cookies');


    }


    public function testCreateFromGlobals()
    {

        $_GET['foo1'] = 'bar1';
        $_POST['foo2'] = 'bar2';
        $_COOKIE['foo3'] = 'bar3';
        $_FILES['foo4'] = array('bar4');
        $_SERVER['foo5'] = 'bar5';

        $request = new Request($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
        $this->assertEquals('bar1', $request->query->get('foo1'), '::fromGlobals() uses values from $_GET');
        $this->assertEquals('bar2', $request->request->get('foo2'), '::fromGlobals() uses values from $_POST');
        $this->assertEquals('bar3', $request->cookies->get('foo3'), '::fromGlobals() uses values from $_COOKIE');
        $this->assertEquals(array('bar4'), $request->files->get('foo4'), '::fromGlobals() uses values from $_FILES');
        $this->assertEquals('bar5', $request->server->get('foo5'), '::fromGlobals() uses values from $_SERVER');

    }

}