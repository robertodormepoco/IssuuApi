Issuu Api library for PHP
===

Just a php library for the publishing service [Issuu](http://issuu.com)

Partially coded and partially working, it needs a massive clean up, refactoring and tests as well (REALLY).

Upload Example
===

the example uses the composer autoloader

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