<?php
namespace TYPO3\Opauth\Opauth;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Opauth".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class Opauth
 * @Flow\Scope("singleton")
 */
class Opauth {

    /**
     * @var \Opauth
     */
    protected $opauth;

    /**
     * @var Configuration
     * @Flow\Inject
     */
    protected $configuration;

    /**
     * @var \TYPO3\Flow\Mvc\ActionRequest
     */
    protected $actionRequest;

    /**
     * @var OpauthResponse
     */
    protected $response;

    /**
     * @param \TYPO3\Flow\Mvc\ActionRequest $actionRequest
     */
    public function setActionRequest(\TYPO3\Flow\Mvc\ActionRequest $actionRequest) {
        $this->actionRequest = $actionRequest;
    }

    /**
     * @return \Opauth
     */
    public function getOpauth() {
        if($this->opauth === NULL) {
            $this->workarounds();
            $config = $this->configuration->getSettings();
            $this->opauth = new \Opauth($config, FALSE);
        }
        return $this->opauth;
    }

    /**
     * @return OpauthResponse
     */
    public function getOpauthResponse() {
        if($this->actionRequest instanceof \TYPO3\Flow\Mvc\ActionRequest && $this->actionRequest->hasArgument('opauth')) {
            $data = $this->actionRequest->getArgument('opauth');
            $response = unserialize(base64_decode($data));
            $this->response = new OpauthResponse($response);
        }

        return $this->response;
    }

    /**
     * Some Workarounds for some strategies
     * @return void
     */
    protected function workarounds() {

        // When canceling a Twitter-Authentication, Flow returns a notice.
        if(isset($_REQUEST['oauth_token']) == FALSE)
            $_REQUEST['oauth_token'] = '';
    }

}

?>