<?php

namespace App\Util;

class ResultResponse
{

    const SUCCESS_CODE = 200;
    const ERROR_CODE = 300;

    const TXT_ERROR_CODE = 'Error';
    const TXT_SUCCESS_CODE = 'Success';

    public $statusCode;
    public $message;
    public $data;

    function __construct()
    {
        $this->statusCode = self::ERROR_CODE;
        $this->message = self::TXT_ERROR_CODE;
        $this->data = '';
    }

     /**
     * Método getter para obtener el valor de $statusCode
     *
     * @return int El valor actual de $statusCode
     */
    public function getStatusCode() {
        return $this->statusCode;
    }

    /**
     * Método setter para establecer el valor de $statusCode
     *
     * @param int $statusCode El nuevo valor de $statusCode
     */
    public function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
    }

    /**
     * Método getter para obtener el valor de $message
     *
     * @return string El valor actual de $message
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * Método setter para establecer el valor de $message
     *
     * @param string $message El nuevo valor de $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }

    /**
     * Método getter para obtener el valor de $data
     *
     * @return array El valor actual de $data
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Método setter para establecer el valor de $data
     *
     * @param array $data El nuevo valor de $data
     */
    public function setData($data) {
        $this->data = $data;
    }
}