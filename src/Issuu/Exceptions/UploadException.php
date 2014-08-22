<?php
/**
 * 
 * Author: Roberto Lombi
 * Date: 20/08/14
 * Time: 18:19
 *
 * Email: signoramiailmio@robertodormepoco.org
 * Website: http://www.robertodormepoco.org
 */

namespace Issuu\Exceptions;

use Issuu\Document\AbstractMethod;

/**
 * Class UploadException
 * @package Issuu\Issuu\Exceptions
 */
class UploadException extends AbstractException {

    /**
     * @param string $code
     * @param int $message
     * @param \Exception $field
     */
    public function __construct($code, $message, $fields) {
        $result = '';

        if(is_array($fields)) {
            foreach($fields as $field) {
                $result .= " " . $field;
            }
        } else {
            $result = $fields;
        }

        parent::__construct($message . " (" . $result . ")");
    }

} 