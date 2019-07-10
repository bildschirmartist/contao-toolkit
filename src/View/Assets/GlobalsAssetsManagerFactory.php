<?php

/**
 * Contao toolkit.
 *
 * @package    contao-toolkit
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015-2018 netzmacht David Molineus.
 * @license    LGPL-3.0-or-later https://github.com/netzmacht/contao-toolkit/blob/master/LICENSE
 * @filesource
 */

declare(strict_types=1);

namespace Netzmacht\Contao\Toolkit\View\Assets;

use function is_array;

/**
 * Class GlobalsAssetsManagerFactory creates the global assets manager.
 *
 * @package Netzmacht\Contao\Toolkit\View\Assets
 */
final class GlobalsAssetsManagerFactory
{
    /**
     * Kernel debug mode.
     *
     * @var bool
     */
    private $debug;

    /**
     * GlobalsAssetsManagerFactory constructor.
     *
     * @param bool $debug Debug mode.
     */
    public function __construct(bool $debug)
    {
        $this->debug = $debug;
    }

    /**
     * Create the assets manager.
     *
     * @return AssetsManager
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function create(): AssetsManager
    {
        foreach (['TL_CSS', 'TL_JAVASCRIPT', 'TL_HEAD', 'TL_BODY'] as $key) {
            if (!isset($GLOBALS[$key]) || !is_array($GLOBALS[$key])) {
                $GLOBALS[$key] = [];
            }
        }

        return new GlobalsAssetsManager(
            $GLOBALS['TL_CSS'],
            $GLOBALS['TL_JAVASCRIPT'],
            $GLOBALS['TL_HEAD'],
            $GLOBALS['TL_BODY'],
            $this->debug
        );
    }
}
