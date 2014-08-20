<?php
/**
 * 
 * Author: Roberto Lombi
 * Date: 20/08/14
 * Time: 17:48
 *
 * Email: signoramiailmio@robertodormepoco.org
 * Website: http://www.robertodormepoco.org
 */

namespace Issuu\Document;

use Issuu\Models\Document;
use Issuu \Document\AbstractMethod;

/**
 * Class Upload
 * @package Issuu\Document
 *
 * {@inheritdoc}
 */
class Upload extends AbstractMethod {

    /**
     * @var Document
     */
    protected $document;

    public function __construct($apiKey, $secret) {
        parent::__construct($apiKey, $secret);

        $this->parameters['apiKey'] = $this->apiKey;
        $this->parameters['action'] = "issuu.document.upload";
    }

    /**
     * @param Document $document
     */
    public function setDocument(Document $document)
    {
        $this->document = $document;
        $this->parameters = $this->document->getParameters();
    }

    /**
     * Execute the upload method
     */
    public function exec()
    {
        $signature = $this->getSignature();

        $this->request($this->document, $signature, $this->apiKey);
    }

    /**
     * @param Document $doc
     * @param $signature
     * @return \SimpleXMLElement
     */
    private function request(Document $doc, $signature)
    {
        $curl = curl_init();

        $post = $doc->getParameters();

        $post['file'] = '@' . $doc->getFilePath();
        $post['signature'] = $signature;
        $post['apiKey'] = $this->apiKey;
        $post['action'] = 'issuu.document.upload';

        curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://upload.issuu.com/1_0',
                CURLOPT_USERAGENT => 'MusicClub Issuu client',
                CURLOPT_POST => 'true',
                CURLOPT_POSTFIELDS => $post,
                CURLOPT_BUFFERSIZE => 128,
                CURLOPT_RETURNTRANSFER => 1,
            ));

        $response = curl_exec($curl);

        curl_close($curl);

        $xmlResponse = simplexml_load_string($response);

        $this->isValid($xmlResponse);

        return $xmlResponse;
    }

} 