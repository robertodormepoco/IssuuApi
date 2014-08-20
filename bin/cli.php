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

$uploader = new \Issuu\Document\Upload($apiKey, $secret);

$doc->setTitle('Test doc');
$doc->setDescription('Lorem ipsum');
$doc->setAccess(\Issuu\Enums\AbstractDocumentAccess::PRIVATE_ACCESS);

$doc->setFilePath('absolute path to your test file');

$uploader->setDocument($doc);

try{
    $uploader->exec();
}catch (\Issuu\Exceptions\AbstractException $e) {
    print_r($e->getMessage());
}