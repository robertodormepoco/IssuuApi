<?php
/**
 * 
 * Author: Roberto Lombi
 * Date: 20/08/14
 * Time: 12:59
 *
 * Email: signoramiailmio@robertodormepoco.org
 * Website: http://www.robertodormepoco.org
 */
include_once __DIR__ . '/../src/IssuuRequest.php';

class TestRequest extends PHPUnit_Framework_TestCase {

    public function testUploadUseHTTPPost() {
        $apiKey = 'apikey';
        $signature = 'signature';
        $parameters = array();
        $action = 'issuu.document.upload';
        $method = 'bla';
        $request = new \Issuu\IssuuRequest($apiKey, $signature, $action, $parameters, $method);
        $this->assertEquals($request->getMethod(), 'post');
    }

    public function testSignatureIsValid() {
        $apiKey = 'apikey';
        $signature = 'signature';
        $parameters = array();
        $action = 'issuu.document.upload';
        $method = 'bla';
        $request = new \Issuu\IssuuRequest($apiKey, $signature, $action, $parameters, $method);

        $signature = $request->getSignature();

    }
}
 