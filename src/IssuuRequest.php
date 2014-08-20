<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lombiroberto
 * Date: 13/11/13
 * Time: 23.07
 * To change this template use File | Settings | File Templates.
 */

namespace Issuu;

/**
 * Class IssuuRequest
 * @package Issuu
 */
class IssuuRequest {

    /**
     * @var string API Key for request authentication
     */
    protected $apiKey;

    /**
     * @var string Signature for request authentication
     */
    protected $signature;

    /**
     * @var string Api action
     */
    protected $action;

    /**
     * @var array Parameters
     */
    protected $parameters;

    /**
     * @var string HTTP method
     */
    protected $method;

    /**
     * @param $apiKey
     * @param $signature
     * @param $action
     * @param array $parameters
     * @param string $method
     */
    public function __construct($apiKey, $signature, $action, $parameters = array(), $method = 'get')
    {
        $this->apiKey = $apiKey;

        $this->signature = $signature;

        $this->action = $action;

        $this->parameters = $parameters;

        $this->method = $action == 'issuu.document.upload' ? 'post' : 'get';
    }

    /**
     * This method generates signatures used in API calls
     *
     * @param $parameters
     * @param $apiKey
     * @param $secret
     * @return string
     */
    public function getSignature($parameters, $secret)
    {
        uksort($parameters, function($aKey, $bKey){
                return strcmp($aKey, $bKey);‡
            });

        $signature = "";

        $signature .= $secret;

        foreach($parameters as $key => $value){
            $signature .= $key . $value;
        }

        $signature = md5($signature);

        return $signature;
    }

    /**
     * This method fires the right HTTP method
     * it returns a response in XML format
     *
     * @return \SimpleXMLElement
     */
    public function getResponse()
    {
        if('get' == $this->method)
            return $this->get();
        else
            return $this->post();
    }

    /**
     * This method executes a HTTP GET request to the api
     * it returns a response in XML format
     *
     * @return \SimpleXMLElement
     */
    private function get()
    {
        $curl = curl_init();

        $get = $this->parameters;

        $getRequest = 'http://api.issuu.com/1_0?';

        foreach($get as $key => $value)
            $getRequest .= '&' . $key . '=' . $value;

        $getRequest .= '&signature=' . $this->signature;


        curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $getRequest,
                CURLOPT_USERAGENT => 'MusicClub Issuu client'
            ));

        if(!$response = curl_exec($curl)){
            print_r('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
        }

        curl_close($curl);

        if('issuu.document_embed.get_html_code' == $this->action) return $response;

        return simplexml_load_string($response);

    }

    /**
     * This method executes a HTTP POST request to the api using cURL
     * it returns a response in XML format
     *
     * @return \SimpleXMLElement
     */
    private function post()
    {
        $curl = curl_init();

        $post = $this->parameters;

        $post['signature'] = $this->signature;

        curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'http://upload.issuu.com/1_0',
                CURLOPT_USERAGENT => 'MusicClub Issuu client',
                CURLOPT_POST => 'true',
                CURLOPT_POSTFIELDS => $post,
            ));

        if(!$response = curl_exec($curl)){
            print_r('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
        }

        curl_close($curl);

        if('issuu.document_embed.get_html_code' == $this->action) return $response;

        return simplexml_load_string($response);
    }

    private function getResponseStatus(\SimpleXMLElement $responseXML)
    {
        foreach($responseXML->attributes() as $attribute)
            if('stat' == $attribute->getName())
                if('ok' == $attribute) {
                    echo "Success" . PHP_EOL;
                } else {
                    echo $responseXML->error['code'] . PHP_EOL;
                    echo $responseXML->error['message'] . PHP_EOL;
                    echo $responseXML->error['field'] . PHP_EOL;
                }
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param array $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $signature
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;
    }
}