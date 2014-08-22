<?php
/**
 * 
 * Author: Roberto Lombi
 * Date: 22/08/14
 * Time: 21:34
 *
 * Email: signoramiailmio@robertodormepoco.org
 * Website: http://www.robertodormepoco.org
 */

namespace Issuu\Exceptions;

/**
 * Class DeleteException
 * @package Issuu\Exceptions
 */
class DeleteException extends AbstractException {

    /**
     * @param string $code
     * @param int $message
     * @param \Exception $fields
     */
    public function __construct($code, $message, $fields) {
        parent::__construct($message . " (" . $fields . ")");
    }
} 