<?php

/**
 *
 * Author: Roberto Lombi
 * Date: 22/08/14
 * Time: 17:35
 *
 * Email: signoramiailmio@robertodormepoco.org
 * Website: http://www.robertodormepoco.org
 */

include_once __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$apiKey = "your apikey";
$secret = "your secret";

$doc = new \Issuu\Models\Document();

$uploader = new \Issuu\Document\Delete($apiKey, $secret);

$doc->setTitle('Test doc');
$doc->setDescription('Lorem ipsum');
$doc->setName('blablabla');

$doc->setAccess(\Issuu\Enums\AbstractDocumentAccess::PRIVATE_ACCESS);

$uploader->addDocument($doc);

try{
    $uploader->exec();
}catch (\Issuu\Exceptions\NotUniqueDocumentException $e) {
    print_r($e->getMessage());
}catch (\Issuu\Exceptions\DeleteException $e) {
    print_r($e->getMessage());
}