<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lombiroberto
 * Date: 17/11/13
 * Time: 12.18
 * To change this template use File | Settings | File Templates.
 */

namespace Issuu\Model;


class DocumentEmbed {

    protected $id;

    protected $dataConfigId;

    protected $documentId;

    protected $readerStartPage;

    protected $width;

    protected $height;

    protected $created;

    public function __construct()
    {

    }

    /**
     * @param mixed $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $dataConfigId
     */
    public function setDataConfigId($dataConfigId)
    {
        $this->dataConfigId = $dataConfigId;
    }

    /**
     * @return mixed
     */
    public function getDataConfigId()
    {
        return $this->dataConfigId;
    }

    /**
     * @param mixed $documentId
     */
    public function setDocumentId($documentId)
    {
        $this->documentId = $documentId;
    }

    /**
     * @return mixed
     */
    public function getDocumentId()
    {
        return $this->documentId;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $readerStartPage
     */
    public function setReaderStartPage($readerStartPage)
    {
        $this->readerStartPage = $readerStartPage;
    }

    /**
     * @return mixed
     */
    public function getReaderStartPage()
    {
        return $this->readerStartPage;
    }

    /**
     * @param mixed $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }


}