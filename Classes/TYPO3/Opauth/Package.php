<?php
namespace TYPO3\Opauth;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Opauth"           *
 *                                                                        */

use TYPO3\Flow\Package\Package as BasePackage;

/**
 * The Extended Authentication Package
 *
 */
class Package extends BasePackage {

    /**
     * Invokes custom PHP code directly after the package manager has been initialized.
     *
     * @param \TYPO3\Flow\Core\Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
        $privatePhpPath = $this->getResourcesPath()
                        . 'Private' . DIRECTORY_SEPARATOR
                        . 'PHP' . DIRECTORY_SEPARATOR;

        define("TYPO3OPAUTH_RESOURCES_PHP_PATH", $privatePhpPath);
        define("TYPO3OPAUTH_PROVIDERNAME", "OpauthProvider");

        $this->loadOpauth();
    }

    /**
     * This method is just a workaround because composer classmaps are not compatible with TYPO3 Flow
     *
     * @return void
     */
    protected function loadOpauth() {
        // Load Opauth from the Libraries directory
        $opauthPath = '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
                    . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Libraries' . DIRECTORY_SEPARATOR
                    . 'opauth'  . DIRECTORY_SEPARATOR . 'opauth'  . DIRECTORY_SEPARATOR
                    . 'lib'     . DIRECTORY_SEPARATOR
                    . 'Opauth'  . DIRECTORY_SEPARATOR
                    . 'Opauth.php';

        require_once __DIR__ . DIRECTORY_SEPARATOR . $opauthPath;
    }

}

?>