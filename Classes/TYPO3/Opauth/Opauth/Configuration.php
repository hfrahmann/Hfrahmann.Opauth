<?php
namespace TYPO3\Opauth\Opauth;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Opauth".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class Configuration
 * @Flow\Scope("singleton")
 */
class Configuration {

    /**
     * @var \TYPO3\Flow\Mvc\Routing\UriBuilder
     */
    protected $uriBuilder;

    /**
     * @var array
     */
    protected $settings = array();

    /**
     * Construct
     */
    public function __construct() {
        $httpRequest = \TYPO3\Flow\Http\Request::createFromEnvironment();
        $actionRequest = $httpRequest->createActionRequest();

        $this->uriBuilder = new \TYPO3\Flow\Mvc\Routing\UriBuilder();
        $this->uriBuilder->setRequest($actionRequest);
    }

    /**
     * @param array $settings
     */
    public function injectSettings(array $settings) {
        $this->settings = $this->mergeSettings($settings);
    }

    /**
     * @return array
     */
    public function getSettings() {
        return $this->settings;
    }

    /**
     * @return string|array|null
     */
    public function getDefaultRoleIdentifier() {
        $key = 'defaultRoleIdentifier';
        return isset($this->settings[$key]) ? $this->settings[$key] : NULL;
    }

    /**
     * @return string
     */
    public function getAuthenticationProviderName() {
        $key = 'authenticationProviderName';
        return isset($this->settings[$key]) ? $this->settings[$key] : NULL;
    }

    /**
     * @param array $settings
     * @return array
     */
    protected function mergeSettings(array $settings) {
        $route = $settings['AuthenticationControllerRoute'];

        $opauthBasePath = '/' . $this->uriBuilder->uriFor(
            'opauth',
            array('strategy' => ''),
            $this->getRoutePart($route, '@controller'),
            $this->getRoutePart($route, '@package'),
            $this->getRoutePart($route, '@subpackage')
        );

        $opauthCallbackPath = '/' . $this->uriBuilder->uriFor(
            'authenticate',
            array(),
            $this->getRoutePart($route, '@controller'),
            $this->getRoutePart($route, '@package'),
            $this->getRoutePart($route, '@subpackage')
        );

        $opauthSettings = array();

        $opauthSettings['defaultRoleIdentifier'] = $settings['defaultRoleIdentifier'];
        $opauthSettings['authenticationProviderName'] = $settings['authenticationProviderName'];

        // should be created with UriBuilder
        $opauthSettings['path'] = $opauthBasePath;

        // should be created with UriBuilder
        $opauthSettings['callback_url'] = $opauthCallbackPath;

        // it must be 'post'
        $opauthSettings['callback_transport'] = 'post';

        // the security salt
        $opauthSettings['security_salt'] = $settings['security_salt'];

        // the strategy directory
        $opauthSettings['strategy_dir'] = TYPO3OPAUTH_RESOURCES_PHP_PATH . 'Strategy' . DIRECTORY_SEPARATOR;

        // import all strategy settings
        $opauthSettings['Strategy'] = $settings['Strategy'];

        return $opauthSettings;
    }

    /**
     * @param array $routeArray
     * @param string $key
     * @return string
     */
    protected function getRoutePart(&$routeArray, $key) {
        if(array_key_exists($key, $routeArray) && strlen($routeArray[$key]) > 0)
            return $routeArray[$key];
        return NULL;
    }

}

?>