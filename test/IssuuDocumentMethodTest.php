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
ini_set('display_errors', 1);
error_reporting(E_ALL);

class IssuuDocumentMethodTest extends PHPUnit_Framework_TestCase {

    protected $apiKey;

    protected $secret;

    protected function setUp()
    {
        parent::setUp();
        $this->apiKey = 'apiKey';
        $this->secret = 'ohshitthisissosecretimnotgoingtotellanyone';
    }


    public function testSignatureWithNoParametersIsValid() {


        $stub = $this->getMockForAbstractClass('\Issuu\Document\MethodAbstract', array($this->apiKey, $this->secret));

        /**
         * {secret} => ohshitthisissosecretimnotgoingtotellanyone
         * {apiKey} => apiKey
         *
         * md5({secret}apiKey{apiKey})
         *
         * md5(ohshitthisissosecretimnotgoingtotellanyoneapiKeyapiKey) = 9e0056792e7e3348a3c252c5e3a1f623
         *
         */

        $this->assertEquals('9e0056792e7e3348a3c252c5e3a1f623', $stub->getSignature());

    }

    public function testSignatureWithParametersIsValid() {

        $uploader = new \Issuu\Document\Upload($this->apiKey, $this->secret);

        $doc = new \Issuu\Models\Document();
        $doc->setTitle('title');
        $doc->setDescription('description');

        $uploader->setDocument($doc);

        /**
         * {secret} => ohshitthisissosecretimnotgoingtotellanyone
         * {action} => issuu.document.upload
         * {apiKey} => apiKey
         * {description} => description
         * {title} => title
         *
         * md5({secret}action{action}apiKey{apiKey}description{description}title{title})
         *
         * md5(ohshitthisissosecretimnotgoingtotellanyoneactionissuu.document.uploadapiKeyapiKeydescriptiondescription
         * titletitle) = 14b3ec8134abe250dcd672843cbadc83
         *
         */

        $this->assertEquals('14b3ec8134abe250dcd672843cbadc83', $uploader->getSignature());
    }

    public function testUploadMethodParameters() {

        $uploader = new \Issuu\Document\Upload($this->apiKey, $this->secret);

        $doc = new \Issuu\Models\Document();
        $doc->setTitle('title');
        $doc->setDescription('description');

        $uploader->setDocument($doc);

        $uploader->setFile('test.pdf');

        $this->assertEquals($uploader->getParameters(), array(
                'apiKey' => 'apiKey',
                'title' => 'title',
                'action' => 'issuu.document.upload',
                'description' => 'description',
                'file' => '@test.pdf'
            ));
    }

    public function testDocumentUrlUploadSignatureIsValid() {

        $slurpUrl = 'http://localhost:8000/test.pdf';

        $urlUploader = new \Issuu\Document\UrlUpload($this->apiKey, $this->secret);
        $urlUploader->setSlurpUrl($slurpUrl);

        $doc = new \Issuu\Models\Document();
        $doc->setTitle('title');
        $doc->setDescription('description');

        $urlUploader->setDocument($doc);

        $this->assertEquals('6dac385df13abe4021e680a7947e03fb', $urlUploader->getSignature());
    }

    public function testAddedDocuments() {
        $deleteAction = new \Issuu\Document\Delete($this->apiKey, $this->secret);

        $a = new \Issuu\Models\Document();

        $a->setName('a');

        $b = new \Issuu\Models\Document();

        $b->setName('b');

        $deleteAction->addDocument($a);
        $deleteAction->addDocument($b);

        $this->assertEquals(array('a' => $a, 'b' => $b), $deleteAction->getDocuments());
    }

    public function testRemovedDocuments() {
        $deleteAction = new \Issuu\Document\Delete($this->apiKey, $this->secret);

        $a = new \Issuu\Models\Document();

        $a->setName('a');

        $deleteAction->removeDocument($a);

        $this->assertEmpty($deleteAction->getDocuments());
    }
}
 