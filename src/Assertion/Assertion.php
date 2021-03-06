<?php

/**
 * Contao toolkit.
 *
 * @package    contao-toolkit
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2017 netzmacht David Molineus. All rights reserved.
 * @license    LGPL-3.0-or-later https://github.com/netzmacht/contao-leaflet-maps/blob/master/LICENSE
 * @filesource
 */

declare(strict_types=1);

namespace Netzmacht\Contao\Toolkit\Assertion;

use Assert\Assertion as BaseAssertion;

/**
 * Class Assertion.
 *
 * @package Netzmacht\Contao\Toolkit\Assertion
 */
class Assertion extends BaseAssertion
{
    /**
     * Exception class.
     *
     * @var string
     */
    protected static $exceptionClass = AssertionFailed::class;
}
