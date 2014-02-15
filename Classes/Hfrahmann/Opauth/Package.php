<?php
namespace Hfrahmann\Opauth;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Hfrahmann.Opauth"       *
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
    }

}

?>