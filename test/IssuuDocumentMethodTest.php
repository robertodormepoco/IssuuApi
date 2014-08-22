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

    public function testSignatureWithNoParametersIsValid() {

        $apiKey = 'apiKey';
        $secret = 'ohshitthisissosecretimnotgoingtotellanyone';

        $stub = $this->getMockForAbstractClass('\Issuu\Document\MethodAbstract', array($apiKey, $secret));

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
        $apiKey = 'apiKey';
        $secret = 'ohshitthisissosecretimnotgoingtotellanyone';

        $uploader = new \Issuu\Document\Upload($apiKey, $secret);

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
        $apiKey = 'apiKey';
        $secret = 'ohshitthisissosecretimnotgoingtotellanyone';

        $uploader = new \Issuu\Document\Upload($apiKey, $secret);

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
        $apiKey = 'apiKey';
        $secret = 'ohshitthisissosecretimnotgoingtotellanyone';

        $slurpUrl = 'http://localhost:8000/test.pdf';

        $urlUploader = new \Issuu\Document\UrlUpload($apiKey, $secret);
        $urlUploader->setSlurpUrl($slurpUrl);

        $doc = new \Issuu\Models\Document();
        $doc->setTitle('title');
        $doc->setDescription('description');

        $urlUploader->setDocument($doc);

        $this->assertEquals('604ce0d7f48b039cad0aeeafc4386490', $urlUploader->getSignature());
    }
}
 