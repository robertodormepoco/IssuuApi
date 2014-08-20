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

class IssuuDocumentMethodTest extends PHPUnit_Framework_TestCase {

    public function testSignatureWithNoParametersIsValid() {

        $apiKey = 'apikey';
        $secret = 'ohshitthisissosecretimnotgoingtotellanyone';

        $stub = $this->getMockForAbstractClass('\Issuu\Document\AbstractMethod', array($apiKey, $secret));

        $this->assertEquals('4b30a53485b72b37008409a5a3d970c9', $stub->getSignature());

    }

    public function testSignatureWithParametersIsValid() {
        $apiKey = 'apikey';
        $secret = 'ohshitthisissosecretimnotgoingtotellanyone';

        $uploader = new \Issuu\Document\Upload($apiKey, $secret);

        $doc = new \Issuu\Models\Document();
        $doc->setTitle('title');
        $doc->setDescription('description');

        $uploader->setDocument($doc);

        $this->assertEquals('4962b858b1ce29f1876e4ba5e2af76c9', $uploader->getSignature());
    }
}
 