<?php
/**
 * Created by JetBrains PhpStorm.
 * User: lombiroberto
 * Date: 28/11/13
 * Time: 15.58
 * To change this template use File | Settings | File Templates.
 */

namespace Issuu\Response;


interface ResponseInterface {
    /**
     * returns the response content
     */
    public function getContent();

    /**
     * check if response is valid
     */
    public function isValid();
}