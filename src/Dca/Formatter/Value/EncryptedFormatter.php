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

namespace Netzmacht\Contao\Toolkit\Dca\Formatter\Value;

use Contao\Encryption;

/**
 * EncryptedFormatter decrypts encrypted values.
 *
 * @package Netzmacht\Contao\Toolkit\Dca\Formatter\Value
 */
final class EncryptedFormatter implements ValueFormatter
{
    /**
     * Encryption instance.
     *
     * @var Encryption
     */
    private $encryption;

    /**
     * EncryptedFormatter constructor.
     *
     * @param Encryption $encryption Encryption service.
     */
    public function __construct(Encryption $encryption)
    {
        $this->encryption = $encryption;
    }

    /**
     * {@inheritDoc}
     */
    public function accepts($fieldName, array $fieldDefinition)
    {
        return !empty($fieldDefinition['eval']['encrypt']);
    }

    /**
     * {@inheritDoc}
     */
    public function format($value, $fieldName, array $fieldDefinition, $context = null)
    {
        return $this->encryption->decrypt($value);
    }
}
