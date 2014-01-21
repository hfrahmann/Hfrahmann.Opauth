<?php
namespace Hfrahmann\Opauth\Authentication;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Hfrahmann.Opauth".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class OpauthProvider
 * @package Hfrahmann\Opauth
 */
class OpauthProvider extends \TYPO3\Flow\Security\Authentication\Provider\AbstractProvider {

    /**
     * @var \TYPO3\Flow\Security\AccountFactory
     * @Flow\Inject
     */
    protected $accountFactory;

    /**
     * @var \TYPO3\Flow\Security\AccountRepository
     * @Flow\Inject
     */
    protected $accountRepository;

    /**
     * @var \Hfrahmann\Opauth\Opauth\Opauth
     * @Flow\Inject
     */
    protected $opauth;

    /**
     * @var \Hfrahmann\Opauth\Opauth\Configuration
     * @Flow\Inject
     */
    protected $configuration;

    /**
     * @var \Hfrahmann\Opauth\Service\OpauthAccountService
     * @Flow\Inject
     */
    protected $accountService;

    /**
     * Returns the classnames of the tokens this provider is responsible for.
     *
     * @return array The classname of the token this provider is responsible for
     */
    public function getTokenClassNames() {
        return array('Hfrahmann\Opauth\Authentication\OpauthToken');
    }

    /**
     * Tries to authenticate the given token. Sets isAuthenticated to TRUE if authentication succeeded.
     *
     * @param \TYPO3\Flow\Security\Authentication\TokenInterface $authenticationToken The token to be authenticated
     * @throws \TYPO3\Flow\Security\Exception\UnsupportedAuthenticationTokenException
     * @return void
     */
    public function authenticate(\TYPO3\Flow\Security\Authentication\TokenInterface $authenticationToken) {
        if (!($authenticationToken instanceof OpauthToken)) {
            throw new \TYPO3\Flow\Security\Exception\UnsupportedAuthenticationTokenException('This provider cannot authenticate the given token.', 1381598908);
        }

        $response = $this->opauth->getResponse();

        if($response !== NULL && $response->isAuthenticationSucceeded()) {
            $accountIdentifier = $this->accountService->createAccountIdentifier($response);
            $authenticationProviderName = $this->name;

            $account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($accountIdentifier, $authenticationProviderName);

            if($account !== NULL) {
                $authenticationToken->setAccount($account);
                $authenticationToken->setAuthenticationStatus(\TYPO3\Flow\Security\Authentication\TokenInterface::AUTHENTICATION_SUCCESSFUL);
            }
        } else {
            $authenticationToken->setAuthenticationStatus(\TYPO3\Flow\Security\Authentication\TokenInterface::NO_CREDENTIALS_GIVEN);
        }
    }

}

?>