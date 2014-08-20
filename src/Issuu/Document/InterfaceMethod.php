<?php
/**
 * 
 * Author: Roberto Lombi
 * Date: 20/08/14
 * Time: 17:52
 *
 * Email: signoramiailmio@robertodormepoco.org
 * Website: http://www.robertodormepoco.org
 */

namespace Issuu\Document;

/**
 * Interface MethodInterface
 * @package Issuu\Document
 */
interface InterfaceMethod {

    /**
     * Instantiate a method with the api key and the secret
     *
     * @param string $apiKey
     * @param string $secret
     */
    public function __construct($apiKey, $secret);

    /**
     * Return the signature of the request
     *
     * @return string
     */
    public function getSignature();

} 