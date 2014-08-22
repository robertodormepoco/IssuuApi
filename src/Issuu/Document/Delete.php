<?php
/**
 * 
 * Author: Roberto Lombi
 * Date: 22/08/14
 * Time: 18:53
 *
 * Email: signoramiailmio@robertodormepoco.org
 * Website: http://www.robertodormepoco.org
 */

namespace Issuu\Document;


use Issuu\Exceptions\DeleteException;
use Issuu\Exceptions\NotUniqueDocumentException;
use Issuu\Exceptions\UploadException;
use Issuu\Models\Document;
use Issuu \Document\MethodAbstract;

class Delete extends MethodAbstract {

    protected $documents;

    public function __construct($apiKey, $secret)
    {
        parent::__construct($apiKey, $secret);

        $this->parameters['action'] = 'issuu.document.delete';
        $this->parameters['documents'] = '';
        $this->documents = array();

    }

    /**
     * @param Document $document
     * @return array
     * @throws \Issuu\Exceptions\NotUniqueDocumentException
     */
    public function addDocument(Document $document) {
        if(!$document->getName()){
            throw new NotUniqueDocumentException('The document is not uniquely identified by a name');
        }

        $this->documents[$document->getName()] = $document;

        return $this->documents;
    }

    public function removeDocument(Document $document) {
        if(isset($this->documents[$document->getName()])) {
            unset($this->documents[$document->getName()]);
        }

        return $this->documents;
    }

    public function getDocuments() {
        return $this->documents;
    }

    private function serializeDocuments() {
        if(count($this->documents) == 0) return '';

        $exploded = array();

        foreach($this->documents as $key => $document) {
            $exploded[] = $key;
        }

        return implode(',', $exploded);
    }

    /**
     * Executes the method
     *
     * @return mixed
     */
    public function exec()
    {
        $this->parameters['names'] = $this->serializeDocuments();
        $signature = $this->getSignature();

        return $this->request($signature);
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
        }catch(DeleteException $e) {
            print_r($e->getMessage());
        }
        return $xmlResponse;
    }
}