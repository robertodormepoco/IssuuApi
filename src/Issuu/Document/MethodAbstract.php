<?php
/**
 * 
 * Author: Roberto Lombi
 * Date: 20/08/14
 * Time: 17:59
 *
 * Email: signoramiailmio@robertodormepoco.org
 * Website: http://www.robertodormepoco.org
 */

namespace Issuu \Document;

use Issuu\Document\MethodInterface;
use Issuu\Exceptions\UploadException;

/**
 * Class AbstractMethod
 * @package Issuu\Issuu\Document
 *
 * {@inheritdoc}
 */
abstract class MethodAbstract implements MethodInterface {

    const ENDPOINT = 'http://upload.issuu.com/1_0';

    protected $secret;

    protected $apiKey;

    protected $parameters;

    /**
     * Instantiate a method with the api key and the secret
     *
     * @param string $apiKey
     * @param string $secret
     */
    public function __construct($apiKey, $secret)
    {
        $this->secret = $secret;
        $this->apiKey = $apiKey;
        $this->parameters = array("apiKey" => $apiKey);
    }


    /**
     * Return the signature of the request
     *
     * @return string
     */
    public function getSignature()
    {
        uksort($this->parameters, function($aKey, $bKey){
                return strcmp($aKey, $bKey);
            });

        $signature = $this->secret;

        foreach($this->parameters as $key => $value){
            if($key != 'file') $signature .= $key . $value;
        }

        echo $signature . PHP_EOL;

        return md5($signature);
    }

    /**
     * @param \SimpleXMLElement $responseXML
     * @throws UploadException
     */
    protected function isValid(\SimpleXMLElement $responseXML)
    {
        foreach($responseXML->attributes() as $attribute)
            /** @var \SimpleXMLElement $attribute */
            if($attribute->getName() == 'stat') {
                if($attribute == 'fail') {
                    throw new UploadException($responseXML->error['code'], $responseXML->error['message'], $responseXML->error['field']);
                }
            }
    }
}