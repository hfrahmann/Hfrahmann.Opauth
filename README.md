TYPO3.Opauth
============

This is a package to use [Opauth](http://opauth.org) with your TYPO3 Flow project.


How to use
----------

1. Installation

 TODO (Composer, Strategies)


2. Authentication Controller

 At first you need an AuthenticationController.
 The *\TYPO3\Opauth\AbstractAuthenticationController* extends the original AbstractAuthenticationController from TYPO3 Flow.

 When you extends the Opauth AbstractAuthenticationController you have to add the following methods to your AuthenticationController.

```
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
```


3. Routing

 The following route is necessary so that Opauth authentication works.
 It have to be pointing to your AuthenticationController.
 You can modify the first part at the uriPattern like you want it.
 But the last part with the *strategy* and the *internalcallback* is important.

```
    -
      name: 'Opauth - Strategy-Login'
      uriPattern: 'opauth/{strategy}(/{internalcallback})'
      defaults:
        '@package': 'My.Package'
        '@controller': 'Authentication'
        '@action': 'opauth' #don't change
        '@format': 'html'
        'internalcallback': '' #important
      appendExceedingArguments: true
```


4. Configuration

 This is the configuration for the *Settings.yaml*
 At first there is the AuthenticationProvider pointing to the OpAuthProvider

 In the Opauth part you have to declare the route to your AuthenticationController. (Same data like in the route from step 3)

 The *defaultRoleIdentifier* is used as the roleIdentifier for a new account.

 For the configuration of the strategies you have to specify them in the *Strategy* area.
 Their are structured like the original Opauth configuration.

```
    TYPO3:

      Flow:
        security:
          authentication:
            authenticationStrategy: oneToken
            providers:

              OpauthProvider:
                provider: 'TYPO3\Opauth\Authentication\OpauthProvider'


      Opauth:

        # The route the AuthenticationController.
        # Must extends the \TYPO3\Opauth\AbstractAuthenticationController.
        AuthenticationControllerRoute:
          '@package': 'My.Package'
          '@subpackage': ''
          '@controller': 'Authentication'
          # No @action required

        defaultRoleIdentifier: 'My.Package:User'
        authenticationProviderName: 'OpauthProvider' #the provider name from top

        # The security_salt must be changed before first use
        security_salt: 'LDFmiilYf8Fyw5W10rx4W1KsVrieQCnpBzzpTBWA5vJidQKDx8pMJbmw28R1C4m'

        Strategy:
          Facebook:
            app_id: '571xxxxxxxxxxx'
            app_secret: '3daxxxxxxxxxxxxxxxxxxxxxxxxxxxx'
            scope: 'email,read_friendlists' # optional
```


Examples
--------

TODO


License
-------

This project is licensed under the MIT-License.