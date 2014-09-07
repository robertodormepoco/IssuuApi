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

use Issuu\Exceptions\UploadException;
use Issuu\Models\Document;
use Issuu \Document\MethodAbstract;

/**
 * Class Upload
 * @package Issuu\Document
 *
 * {@inheritdoc}
 */
class Upload extends MethodAbstract {

    /**
     * @var Document
     */
    protected $document;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param string $apiKey
     * @param string $secret
     */
    public function __construct($apiKey, $secret) {
        parent::__construct($apiKey, $secret);

        $this->parameters["action"] = "issuu.document.upload";
    }

    /**
     * @param Document $document
     */
    public function setDocument(Document $document)
    {
        $this->document = $document;

        $this->parameters = array_merge($this->parameters, $this->document->getParameters());
    }

    /**
     * @param $filePath
     */
    public function setFile($filePath) {
        $this->parameters['file'] = '@' . $filePath;
    }

    /**
     * Execute the upload method
     */
    public function exec()
    {
        $signature = $this->getSignature();

        $this->request($signature);
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param $signature
     * @return \SimpleXMLElement
     */
    private function request($signature)
    {
        $curl = curl_init();

        $post = array_merge($this->parameters, array("signature" => $signature));

        $fields = array();

        foreach($post as $key => $value) {
            if($value instanceof \DateTime) {
                $fields[] = $key . '=' . $value->format('YYYY-MM-DD');
            } else {
                $fields[] = $key . '=' . $value;
            }
        }

        $result = implode('&', $fields);

        curl_setopt_array($curl, array(
                CURLOPT_URL => MethodAbstract::ENDPOINT,
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