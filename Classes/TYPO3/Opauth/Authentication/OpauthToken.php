<?php
namespace TYPO3\Opauth\Authentication;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Opauth".          *
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
     * @var \TYPO3\Opauth\Opauth\Opauth
     * @Flow\Inject
     */
    protected $opauth;

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
        $response = $this->opauth->getOpauthResponse();

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