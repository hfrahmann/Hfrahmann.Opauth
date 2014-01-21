<?php
namespace Hfrahmann\Opauth\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Hfrahmann.Opauth".          *
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
 * {namespace opauth=Hfrahmann\Opauth\ViewHelpers}
 *
 * {opauth:opauthStrategyUri(strategy:'facebook')}
 * </code>
 * <output>
 * /opauth/facebook
 * </output>
 */
class OpauthStrategyUriViewHelper extends AbstractViewHelper {

    /**
     * @var \Hfrahmann\Opauth\Opauth\Configuration
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