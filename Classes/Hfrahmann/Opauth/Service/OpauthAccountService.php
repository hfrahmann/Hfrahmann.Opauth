<?php
namespace Hfrahmann\Opauth\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Hfrahmann.Opauth".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class OpauthResponse
 * @Flow\Scope("singleton")
 */
class OpauthAccountService {

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
     * @var \Hfrahmann\Opauth\Opauth\Configuration
     * @Flow\Inject
     */
    protected $configuration;

    /**
     * Creates an account identifier with the strategy and the unique userID.
     *
     * @param \Hfrahmann\Opauth\Opauth\Response $opauthResponse
     * @return string
     * @throws \Hfrahmann\Opauth\Exception
     */
    public function createAccountIdentifier(\Hfrahmann\Opauth\Opauth\Response $opauthResponse) {
        if($opauthResponse == NULL)
            throw new \Hfrahmann\Opauth\Exception("OpauthResponse cannot be NULL.", 1381596920);

        $strategy = $opauthResponse->getStrategy();
        $userID = $opauthResponse->getUserID();

        if(strlen($strategy) > 0 && strlen($userID) > 0) {
            return $strategy . ':' . $userID;
        } else {
            throw new \Hfrahmann\Opauth\Exception("No Strategy or UserID given.", 1381596915);
        }
    }

    /**
     * Return an OPAuth account.
     * If an account with the given data does not exist a new account will be created.
     *
     * @param \Hfrahmann\Opauth\Opauth\Response $opauthResponse
     * @return \TYPO3\Flow\Security\Account
     * @throws \Hfrahmann\Opauth\Exception
     */
    public function getAccount(\Hfrahmann\Opauth\Opauth\Response $opauthResponse) {
        if($opauthResponse == NULL)
            throw new \Hfrahmann\Opauth\Exception("OpauthResponse cannot be NULL.", 1381596921);

        $accountIdentifier = $this->createAccountIdentifier($opauthResponse);
        $authenticationProviderName = $this->configuration->getAuthenticationProviderName();

        $account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($accountIdentifier, $authenticationProviderName);

        if($account === NULL) {
            $roleIdentifier = $this->configuration->getDefaultRoleIdentifier();
            $roleIdentifierArray = array();
            if(is_array($roleIdentifier))
                $roleIdentifierArray = $roleIdentifier;
            if(is_string($roleIdentifier))
                $roleIdentifierArray = array($roleIdentifier);

            $account = $this->accountFactory->createAccountWithPassword($accountIdentifier, NULL, $roleIdentifierArray, $authenticationProviderName);
        }

        return $account;
    }

    /**
     * Checks if the given account is already in the account repository
     *
     * @param \TYPO3\Flow\Security\Account $account
     * @return bool
     */
    public function doesAccountExist(\TYPO3\Flow\Security\Account $account) {
        $accountIdentifier = $account->getAccountIdentifier();
        $authenticationProviderName = $account->getAuthenticationProviderName();

        $existingAccount = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($accountIdentifier, $authenticationProviderName);
        return ($existingAccount !== NULL);
    }

}

?>