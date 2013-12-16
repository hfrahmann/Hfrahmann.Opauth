<?php
namespace TYPO3\Opauth\Opauth;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Opauth".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class OpauthResponse
 * @package TYPO3\Opauth\Opauth
 */
class Response {

    /**
     * @var array
     */
    protected $responseData = array();

    /**
     * @param array $responseData
     */
    public function __construct(array $responseData) {
        $this->responseData = $responseData;
    }

    /**
     * @return array
     */
    public function getRawData() {
        return $this->responseData;
    }

    /**
     * @return string
     */
    public function getStrategy() {
        if(isset($this->responseData['auth']['provider']))
            return $this->responseData['auth']['provider'];
        return '';
    }

    /**
     * @return string
     */
    public function getUserID() {
        if(isset($this->responseData['auth']['uid']))
            return (string)$this->responseData['auth']['uid'];
        return '';
    }

    /**
     * @return bool
     */
    public function isAuthenticationSucceeded() {
        if(array_key_exists('auth', $this->responseData) && array_key_exists('error', $this->responseData) === FALSE)
            return TRUE;
        return FALSE;
    }

}