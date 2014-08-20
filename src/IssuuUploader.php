<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lombiroberto
 * Date: 13/11/13
 * Time: 15.13
 * To change this template use File | Settings | File Templates.
 */
namespace Issuu;

use Issuu\Model\Document;

class IssuuUploader {

    protected $apiKey;

    protected $secret;

    protected $signature;

    protected $documents;

    private $stream;

    public function __construct($apiKey, $secret)
    {
        $this->apiKey = $apiKey;
        $this->secret = $secret;
        $this->documents = array();
    }

    public function addDocument(IssuuDocument $document)
    {
        $this->documents[] = $document;
    }

    public function publishDocuments()
    {
        foreach($this->documents as $doc)
            if($doc instanceof IssuuDocument) {

                $parameters = $doc->getParameters();

                $parameters['apiKey'] = $this->apiKey;
                $parameters['action'] = "issuu.document.upload";

                $signature = $this->getSignature($parameters, $this->apiKey, $this->secret);

                $this->doRequest($doc, $signature, $this->apiKey);
            }

    }

    private function getSignature($parameters, $apiKey, $secret)
    {

        uksort($parameters, function($aKey, $bKey){
                return strcmp($aKey, $bKey);
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
     * @param IssuuDocument $doc
     * @param $signature
     * @param $apiKey
     *
     * @TODO: change method signature to something like this doRequest(IssuuRequest $request, $apiKey)
     */
    private function doRequest(IssuuDocument $doc, $signature, $apiKey)
    {
        $this->stream = fopen('php://stdout', 'w');
        $curl = curl_init();

        $post = $doc->getParameters();

        $post['file'] = '@' . $doc->getFilePath();
        $post['signature'] = $signature;
        $post['apiKey'] = $apiKey;
        $post['action'] = 'issuu.document.upload';

        curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://upload.issuu.com/1_0',
                CURLOPT_USERAGENT => 'MusicClub Issuu client',
                CURLOPT_POST => 'true',
                CURLOPT_POSTFIELDS => $post,
                CURLOPT_NOPROGRESS => false,
                CURLOPT_PROGRESSFUNCTION => 'self::progressCallback',
                CURLOPT_BUFFERSIZE => 128,
                CURLOPT_RETURNTRANSFER => 1,
            ));

        $response = curl_exec($curl);

        curl_close($curl);
        fclose($this->stream);

        return simplexml_load_string($response);
    }

    /**
     * @param $curl
     * @param $fileDescriptor
     * @param $length
     */
    public function progressCallback($download, $downloaded, $upload, $uploaded)
    {
        if($upload != 0) {
            @fwrite($this->stream, "Progress: " . ceil(100*$uploaded/$upload) . "%\x0D");
            @fwrite($this->stream, "\x0D");
            fflush($this->stream);
        }
    }

    public function doDocumentsList()
    {
        $curl = curl_init();

        $get = array();

        $get['startIndex'] = 0;
        $get['pageSize'] = 5;
        $get['apiKey'] = $this->apiKey;
        $get['action'] = "issuu.documents.list";

        $get['signature'] = $this->getSignature($get, $this->apiKey, $this->secret);

        $getRequest = 'http://api.issuu.com/1_0?';

        foreach($get as $key => $value)
            $getRequest .= '&' . $key . '=' . $value;

        curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $getRequest,
                CURLOPT_USERAGENT => 'MusicClub Issuu client'
            ));

        if(!$response = curl_exec($curl)){
            print_r('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
        }

        curl_close($curl);

        $responseXML = @simplexml_load_string($response);

        return $this->getDocumentsList($responseXML);
    }

    private function getResponseStatus(\SimpleXMLElement $responseXML)
    {
        foreach($responseXML->attributes() as $attribute)
            if($attribute->getName() == 'stat')
                if($attribute == 'ok') {
                    echo "Success" . PHP_EOL;
                } else {
                    echo $responseXML->error['code'] . PHP_EOL;
                    echo $responseXML->error['message'] . PHP_EOL;
                    echo $responseXML->error['field'] . PHP_EOL;
                }
    }

    /**
     * Ritorna un array di Issuu\Model\Document rappresentanti i documenti caricati su Issuu, torna false
     *
     * @param \SimpleXMLElement $responseXML
     * @return array|bool
     */
    private function getDocumentsList(\SimpleXMLElement $responseXML)
    {

        $documents = array();

        if($responseXML->getName() != 'rsp' || $responseXML->attributes()['stat'] != 'ok')
            throw new \Exception('There\'s something wrong with the response, node =>' . $responseXML->getName() . ', value => ' . $responseXML->attributes()['stat']);

        foreach($responseXML->result->document as $document)
        {
            $doc = new Document();
            foreach($document->attributes() as $key => $attribute) {
                if(method_exists($doc, 'set' . ucwords($key)))
                    $doc->{'set' . ucwords($key)}($attribute);
            }
            $documents[] = $doc;
        }

        return $documents;
    }
}