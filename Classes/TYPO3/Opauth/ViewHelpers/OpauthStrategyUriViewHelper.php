<?php
namespace TYPO3\Opauth\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Opauth".          *
 *                                                                        *
 *                                                                        */

use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\Fluid\Core\ViewHelper;
use TYPO3\Flow\Annotations as Flow;

/**
 * A view helper for creating URIs for OPAuth-Actions
 *
 * = Examples =
 *
 * <code title="Defaults">
 * {namespace opauth=TYPO3\Opauth\ViewHelpers}
 *
 * {opauth:opauthStrategyUri(strategy:'facebook')}
 * </code>
 * <output>
 * /opauth/facebook
 * </output>
 */
class OpauthStrategyUriViewHelper extends AbstractViewHelper {

    /**
     * @var \TYPO3\Opauth\Opauth\Configuration
     * @Flow\Inject
     */
    protected $opauthConfiguration;

    /**
     * @param string $strategy
     * @return string
     */
    public function render($strategy = '') {
        $opauthSettings = $this->opauthConfiguration->getConfiguration();

        $uri = $opauthSettings['path'] . $strategy;

        return $uri;
    }

}

?>