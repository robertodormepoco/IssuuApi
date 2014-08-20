<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lombiroberto
 * Date: 17/11/13
 * Time: 12.22
 * To change this template use File | Settings | File Templates.
 */

namespace Issuu\Model;

class Document {
    protected $username; //	Owner of document

    protected $name;   // Combined with username this defines documents location on Issuu: http://issuu.com/<username>/docs/<name>

    protected $documentId;  // Unique identifier of the document

    protected $title; // Title of the document

    protected $access; // "public" or "private" - enum

    protected $state; // The state of the document - enum

    protected $errorCode; // If document has failed conversion this parameter will give more information about the reason. See Error Codes

    protected $category; // Category to which the content belongs

    protected $type; // Physical format of publications

    protected $orgDocType; // Format of original file

    protected $orgDocName; // The original filename of the uploaded document

    protected $origin; // The source of the document

    protected $language; // Language Code for the document

    protected $pageCount; // The number of pages in the document

    protected $publishDate; // Timestamp for when this document was published

    protected $description; //	Description of the content

    protected $tags; // Keywords describing the document

    protected $warnings; // Properties of the original file which could have affected the quality of the finished document. See Warning Codes

    protected $folders; // The folders containing this document

    public function __construct()
    {

    }

    /**
     * @param mixed $access
     */
    public function setAccess($access)
    {
        $this->access = $access;
    }

    /**
     * @return mixed
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
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
     * @param mixed $errorCode
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param mixed $folders
     */
    public function setFolders($folders)
    {
        $this->folders = $folders;
    }

    /**
     * @return mixed
     */
    public function getFolders()
    {
        return $this->folders;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $orgDocName
     */
    public function setOrgDocName($orgDocName)
    {
        $this->orgDocName = $orgDocName;
    }

    /**
     * @return mixed
     */
    public function getOrgDocName()
    {
        return $this->orgDocName;
    }

    /**
     * @param mixed $orgDocType
     */
    public function setOrgDocType($orgDocType)
    {
        $this->orgDocType = $orgDocType;
    }

    /**
     * @return mixed
     */
    public function getOrgDocType()
    {
        return $this->orgDocType;
    }

    /**
     * @param mixed $origin
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;
    }

    /**
     * @return mixed
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param mixed $pageCount
     */
    public function setPageCount($pageCount)
    {
        $this->pageCount = $pageCount;
    }

    /**
     * @return mixed
     */
    public function getPageCount()
    {
        return $this->pageCount;
    }

    /**
     * @param mixed $publishDate
     */
    public function setPublishDate($publishDate)
    {
        $this->publishDate = $publishDate;
    }

    /**
     * @return mixed
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $warnings
     */
    public function setWarnings($warnings)
    {
        $this->warnings = $warnings;
    }

    /**
     * @return mixed
     */
    public function getWarnings()
    {
        return $this->warnings;
    }


}