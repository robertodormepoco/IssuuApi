<?php

/**
 *
 * Author: Roberto Lombi
 * Date: 20/08/14
 * Time: 19:11
 *
 * Email: signoramiailmio@robertodormepoco.org
 * Website: http://www.robertodormepoco.org
 */

include_once __DIR__ . '/../vendor/autoload.php';

$apiKey = "your api key";
$secret = "your secret";

$doc = new \Issuu\Models\Document();

$uploader = new \Issuu\Document\UrlUpload($apiKey, $secret);

$doc->setTitle('Test title');
$doc->setDescription('Test description');

$uploader->setSlurpUrl('http://www.education.gov.yk.ca/pdf/pdf-test.pdf');

$uploader->setDocument($doc);

try{
    $uploader->exec();
}catch (\Issuu\Exceptions\AbstractException $e) {
    print_r($e->getMessage());
}