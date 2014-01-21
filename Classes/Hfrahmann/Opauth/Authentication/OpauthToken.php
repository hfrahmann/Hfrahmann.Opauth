<?php
namespace Hfrahmann\Opauth\Authentication;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Hfrahmann.Opauth".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * An authentication token
 */
class OpauthToken extends \TYPO3\Flow\Security\Authentication\Token\AbstractToken {

    /**
     * @var string
     */
    protected $strategy = '';

    /**
     * @var \Hfrahmann\Opauth\Opauth\Opauth
     */
    protected $opauth;

    /**
     * @var array
     */
    protected $opauthResponse;

    /**
     * @param \Hfrahmann\Opauth\Opauth\Opauth $opauth
     */
    public function injectOpauth(\Hfrahmann\Opauth\Opauth\Opauth $opauth) {
        $this->opauth = $opauth;
        if($opauth !== NULL && $opauth->getResponse() !== NULL)
            $this->opauthResponse = $opauth->getResponse()->getRawData();
    }

    /**
     * @return array Returns the response data from opauth
     */
    public function getOpauthResponse() {
        return $this->opauthResponse;
    }

    /**
     * Updates the authentication credentials, the authentication manager needs to authenticate this token.
     * This could be a username/password from a login controller.
     * This method is called while initializing the security context. By returning TRUE you
     * make sure that the authentication manager will (re-)authenticate the tokens with the current credentials.
     * Note: You should not persist the credentials!
     *
     * @param \TYPO3\Flow\Mvc\ActionRequest $actionRequest The current request instance
     * @return void
     */
    public function updateCredentials(\TYPO3\Flow\Mvc\ActionRequest $actionRequest) {
        $this->opauth->setActionRequest($actionRequest);
        $response = $this->opauth->getResponse();

        if($response !== NULL) {
            $this->strategy = $response->getStrategy();
            $this->setAuthenticationStatus(self::AUTHENTICATION_NEEDED);
        }
        return;
    }

    /**
     * @return string
     */
    public function __toString() {
        return 'OpauthToken: ' . $this->strategy;
    }
}

?>