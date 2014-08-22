<?php
/**
 * 
 * Author: Roberto Lombi
 * Date: 21/08/14
 * Time: 16:13
 *
 * Email: signoramiailmio@robertodormepoco.org
 * Website: http://www.robertodormepoco.org
 */

namespace Issuu\Document;


use Issuu\Exceptions\UploadException;
use Issuu\Models\Document;
use Issuu \Document\MethodAbstract;

/**
 * Class UrlUpload
 * @package Issuu\Document
 */
class UrlUpload extends MethodAbstract {

    protected $document;

    protected $parameters;

    /**
     * @param string $apiKey
     * @param string $secret
     */
    public function __construct($apiKey, $secret) {
        parent::__construct($apiKey, $secret);

        $this->parameters['action'] = 'issuu.document.url_upload';

    }

    /**
     * @param Document $document
     */
    public function setDocument(Document $document) {
        $this->document = $document;
        $this->parameters = array_merge($this->parameters, $this->document->getParameters());
    }

    /**
     * @param $slurpUrl
     */
    public function setSlurpUrl($slurpUrl) {
        $this->parameters['slurpUrl'] = $slurpUrl;
    }

    /**
     * Executes the url upload
     *
     * @return mixed
     */
    public function exec()
    {
        $signature = $this->getSignature();

        $this->request($signature);
    }

    /**
     * @param $signature
     * @return \SimpleXMLElement
     */
    private function request($signature) {
        $curl = curl_init();

        $post = array_merge($this->parameters, array("signature" => $signature));

        $fields = array();

        foreach($post as $key => $value) {
            $fields[] = $key . '=' . $value;
        }

        $result = implode('&', $fields);

        curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://api.issuu.com/1_0',
                CURLOPT_USERAGENT => 'MusicClub Issuu client',
                CURLOPT_POST => 'true',
                CURLOPT_POSTFIELDS => $result,
                CURLOPT_BUFFERSIZE => 128,
                CURLOPT_RETURNTRANSFER => 1,
            ));

        $response = curl_exec($curl);

        curl_close($curl);

        $xmlResponse = simplexml_load_string($response);

        try {
            $this->isValid($xmlResponse);
        }catch(UploadException $e) {
            print_r($e->getMessage());
        }

        return $xmlResponse;
    }
}