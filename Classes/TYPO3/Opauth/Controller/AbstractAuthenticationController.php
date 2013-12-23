<?php
namespace TYPO3\Opauth\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Opauth".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

abstract class AbstractAuthenticationController extends \TYPO3\Flow\Security\Authentication\Controller\AbstractAuthenticationController {

    /**
     * @var \TYPO3\Opauth\Opauth\Opauth
     */
    private $opauth;

    /**
     * @var \TYPO3\Opauth\Service\OpauthAccountService
     */
    private $opauthAccountService;

    /**
     * @var \TYPO3\Opauth\Opauth\Configuration
     */
    private $opauthConfiguration;

    /**
     * @var bool
     */
    private $authenticateActionAlreadyCalled = FALSE;

    /**
     * @var array Contains the complete response data from Opauth
     */
    protected $opauthResponse = array();

    /**
     * @param \TYPO3\Opauth\Opauth\Opauth $opauth
     */
    public function injectOpauth(\TYPO3\Opauth\Opauth\Opauth $opauth) {
        $this->opauth = $opauth;
        if($opauth !== NULL && $opauth->getResponse() !== NULL)
            $opauth->getResponse()->getRawData();
    }

    /**
     * @param \TYPO3\Opauth\Service\OpauthAccountService $opauthAccountService
     */
    public function injectOpauthAccountService(\TYPO3\Opauth\Service\OpauthAccountService $opauthAccountService) {
        $this->opauthAccountService = $opauthAccountService;
    }

    /**
     * @param \TYPO3\Opauth\Opauth\Configuration $opauthConfiguration
     */
    public function injectOpauthConfiguration(\TYPO3\Opauth\Opauth\Configuration $opauthConfiguration) {
        $this->opauthConfiguration = $opauthConfiguration;
    }

    /**
     * Run Opauth to authenticate with the given strategy.
     *
     * @param string $strategy
     * @param string $internalcallback
     * @return string
     */
    public function opauthAction($strategy, $internalcallback = '') {
        $this->opauth->getOpauth()->run();
        return '';
    }

    /**
     * Overridden authenticateAction method to check for an existing account with the Opauth data.
     *
     * @return string
     */
    public function authenticateAction() {
        $opauthResponse = $this->opauth->getResponse();

        if($this->authenticateActionAlreadyCalled == FALSE && $opauthResponse !== NULL) {
            $this->authenticateActionAlreadyCalled = TRUE;
            if($opauthResponse->isAuthenticationSucceeded()) {
                $opauthAccount = $this->opauthAccountService->getAccount($opauthResponse);
                $doesAccountExists = $this->opauthAccountService->doesAccountExist($opauthAccount);

                if($doesAccountExists === FALSE) {
                    return $this->onOpauthAccountDoesNotExist($opauthResponse->getRawData(), $opauthAccount);
                }
            } else {
                return $this->onOpauthAuthenticationFailure($opauthResponse->getRawData());
            }
        }

        return parent::authenticateAction();
    }

    /**
     * This method is called when the account does not exist in the TYPO3 Flow Account Repository.
     * You can show an addition formular for registration or add the account directly to the Account Repository.
     * If you add the account to the Repository you have to authenticate again manually.
     *
     * @param array $opauthResponseData Opauth Response with all sent data depends on the used strategy (facebook, twitter, ...)
     * @param \TYPO3\Flow\Security\Account $opauthAccount A pre-generated account with the Opauth data
     * @return void|string
     */
    abstract public function onOpauthAccountDoesNotExist(array $opauthResponseData, \TYPO3\Flow\Security\Account $opauthAccount);

    /**
     * This method is called when the authentication was cancelled or another problem occurred at the provider.
     *
     * @param array $opauthResponseData
     * @return void|string
     */
    abstract public function onOpauthAuthenticationFailure(array $opauthResponseData);

}

?>
