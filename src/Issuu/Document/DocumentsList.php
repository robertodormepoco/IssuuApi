<?php
/**
 * 
 * Author: Roberto Lombi
 * Date: 20/08/14
 * Time: 17:49
 *
 * Email: signoramiailmio@robertodormepoco.org
 * Website: http://www.robertodormepoco.org
 */

namespace Issuu\Document;

use Issuu\Models\Document;

/**
 * Class DocumentsList
 * @package Issuu\Document
 */
class DocumentsList extends AbstractMethod {

    protected $documents;

    protected $documentStates;

    protected $access;

    protected $origins;

    protected $orgDocType;

    protected $orgDocName;

    protected $resultOrder;

    protected $startIndex;

    protected $pageSize;

    protected $documentSortBy;

    /**
     * @return mixed
     */
    public function exec()
    {
        $curl = curl_init();

        $get = array();

        $get['startIndex'] = $this->startIndex;
        $get['pageSize'] = $this->pageSize;
        $get['apiKey'] = $this->apiKey;
        $get['action'] = "issuu.documents.list";

        $get['signature'] = $this->getSignature();

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