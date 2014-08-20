<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lombiroberto
 * Date: 15/11/13
 * Time: 13.56
 * To change this template use File | Settings | File Templates.
 */

namespace Issuu;

use Issuu\Model\Document;
use Issuu\Model\DocumentEmbed;

/**
 * Class IssuuClient
 * the client takes a IssuuRequest and it gives back a IssuuResponse, fair enough
 *
 * @package Issuu
 */
class IssuuClient {

    protected $apiKey;

    protected $secret;

    /**
     * @var IssuuRequest
     */
    protected $request;

    /**
     * @var IssuuResponse
     */
    protected $response;

    public function __construct($apiKey, $secret)
    {
        $this->apiKey = $apiKey;

        $this->secret = $secret;

        $this->request = null;

        $this->response = null;
    }

    public function setRequest(IssuuRequest $request)
    {
        $this->request = $request;
    }

    public function getResponse()
    {
        return $this->request->getResponse();
    }

    public function embedDocument(Document $document)
    {
        $parameters = array();
        $parameters['documentId'] = utf8_encode($document->getDocumentId());
        $parameters['apiKey'] = utf8_encode($this->apiKey);
        $parameters['action'] = utf8_encode('issuu.document_embed.add');
        $parameters['readerStartPage'] = utf8_encode(0);
        $parameters['width'] = utf8_encode(640);
        $parameters['height'] = utf8_encode(480);

        $signature = $this->generateSignature($this->secret, $parameters);

        $this->request = new IssuuRequest($this->apiKey, $signature, $parameters['action'], $parameters);

        return $this->request->getResponse();
    }

    /**
     * @param array $ids
     * @return \SimpleXMLElement
     */
    public function deleteEmbededDocuments($ids = array())
    {
        $parameters = array();
        $parameters['apiKey'] = utf8_encode($this->apiKey);
        $parameters['action'] = utf8_encode('issuu.document_embed.delete');


        foreach($ids as $id) {
            $parameters['embedId'] = utf8_encode($id);
            $signature = $this->generateSignature($this->secret, $parameters);
            $this->request = new IssuuRequest($this->apiKey, $signature, $parameters['action'], $parameters);
            $response[$id] = $this->request->getResponse();
        }

        return $response;
    }

    public function getEmbedDocumentHtml($id)
    {
        $parameters = array();
        $parameters['apiKey'] = utf8_encode($this->apiKey);
        $parameters['action'] = utf8_encode('issuu.document_embed.get_html_code');
        $parameters['embedId'] = utf8_encode($id);

        $signature = $this->generateSignature($this->secret, $parameters);

        $this->request = new IssuuRequest($this->apiKey, $signature, $parameters['action'], $parameters);

        return $this->request->getResponse();
    }

    public function getEmbededDocuments()
    {
        $parameters = array();
        $parameters['apiKey'] = utf8_encode($this->apiKey);
        $parameters['action'] = utf8_encode('issuu.document_embeds.list');

        $signature = $this->generateSignature($this->secret, $parameters);

        $this->request = new IssuuRequest($this->apiKey, $signature, $parameters['action'], $parameters);

        return $this->hidrateEmbedDocuments($this->request->getResponse());
    }

    /**
     * This method updates a Issuu\Model\DocumentEmbed
     *
     * @param DocumentEmbed $embedDoc
     * @return array
     */
    public function updateEmbededDocument(DocumentEmbed $embedDoc)
    {
        $parameters = array();
        $parameters['apiKey'] = utf8_encode($this->apiKey);
        $parameters['action'] = utf8_encode('issuu.document_embed.update');
        $parameters['embedId'] = utf8_encode($embedDoc->getId());

        $signature = $this->generateSignature($this->secret, $parameters);

        $this->request = new IssuuRequest($this->apiKey, $signature, $parameters['action'], $parameters);

        return $this->hidrateEmbedDocuments($this->request->getResponse());
    }

    /**
     * Generates signature based on request parameters
     *
     * @param $secret
     * @param $parameters
     * @return string
     */
    private function generateSignature($secret, $parameters)
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
     * This method hidrates a collection of Issuu\Model\DocumentEmbed
     *
     * @param \SimpleXMLElement $responseXML
     * @return array
     * @throws \Exception
     */
    private function hidrateEmbedDocuments(\SimpleXMLElement $responseXML)
    {
        $documents = array();

        if($responseXML->getName() != 'rsp' || $responseXML->attributes()['stat'] != 'ok')
            throw new \Exception('There\'s something wrong with the response, node =>' . $responseXML->getName() . ', value => ' . $responseXML->attributes()['stat']);

        foreach($responseXML->result->documentEmbed as $documentEmbed)
        {
            $docE = new DocumentEmbed();
            foreach($documentEmbed->attributes() as $key => $attribute) {
                if(method_exists($docE, 'set' . ucwords($key)))
                    $docE->{'set' . ucwords($key)}($attribute);
            }
            $documents[] = $docE;
        }

        return $documents;
    }


}