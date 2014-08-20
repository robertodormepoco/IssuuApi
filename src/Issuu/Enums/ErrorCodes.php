<?php
/**
 * 
 * Author: Roberto Lombi
 * Date: 20/08/14
 * Time: 17:35
 *
 * Email: signoramiailmio@robertodormepoco.org
 * Website: http://www.robertodormepoco.org
 */

namespace Issuu\Enums;

/**
 * Class ErrorCodes
 * @package Issuu\Enums
 */
abstract class ErrorCodes {
    const AUTHENTICATION_REQUIRED = '009';
    const INVALID_API_KEY = '010';
    const REQUIRED_FIELD_MISSING = '200';
    const INVALID_FIELD_FORMAT = '201';
    const FILE_SIZE_IS_TOO_LARGE = '205';
    const EXCEEDING_ALLOWED_AMOUNT_OF_UNLISTED_PUBLICATIONS = '294';
    const NAME_PARAMETER_ALREADY_IN_USE = '302';
}