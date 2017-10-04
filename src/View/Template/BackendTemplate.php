<?php

/**
 * Contao toolkit.
 *
 * @package    contao-toolkit
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015-2017 netzmacht David Molineus.
 * @license    LGPL-3.0 https://github.com/netzmacht/contao-toolkit/blob/master/LICENSE
 * @filesource
 */

namespace Netzmacht\Contao\Toolkit\View\Template;

use Contao\BackendTemplate as ContaoBackendTemplate;
use Netzmacht\Contao\Toolkit\View\Template;

/**
 * BackendTemplate with extended features.
 */
final class BackendTemplate extends ContaoBackendTemplate implements Template
{
    use TemplateTrait;

    /**
     * TemplateTrait constructor.
     *
     * @param string     $name        The template name.
     * @param callable[] $helpers     View helpers.
     * @param string     $contentType The content type.
     */
    public function __construct($name, $helpers = [], $contentType = 'text/html')
    {
        parent::__construct($name, $contentType);

        $this->helpers = $helpers;
    }
}
