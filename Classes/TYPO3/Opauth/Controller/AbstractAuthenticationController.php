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
     * @Flow\Inject
     */
    protected $opauth;

    /**
     * @var \TYPO3\Opauth\Service\OpauthAccountService
     * @Flow\Inject
     */
    protected $opauthAccountService;

    /**
     * @var \TYPO3\Opauth\Opauth\Configuration
     * @Flow\Inject
     */
    protected $opauthConfiguration;

    /**
     * @var bool
     */
    private $authenticateActionAlreadyCalled = FALSE;

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
        $opauthResponse = $this->opauth->getOpauthResponse();

        if($this->authenticateActionAlreadyCalled == FALSE && $opauthResponse !== NULL) {
            $this->authenticateActionAlreadyCalled = TRUE;
            if($opauthResponse->isAuthenticationSucceeded()) {
                $opauthAccount = $this->opauthAccountService->getAccount($opauthResponse);
                $doesAccountExists = $this->opauthAccountService->isAccountExisting($opauthAccount);

                if($doesAccountExists === FALSE) {
                    return $this->onOpauthAccountDoesNotExist($opauthResponse->getRawData(), $opauthAccount);
                }
            } else {
                return $this->onOpauthAuthenticationCanceled();
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
     * This method is called when the authentication was cancelled at the provider.
     *
     * @return void|string
     */
    abstract public function onOpauthAuthenticationCanceled();

}

?>