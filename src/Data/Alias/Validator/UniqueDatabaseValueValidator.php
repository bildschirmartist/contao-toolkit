<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Contao\Toolkit\Data\Alias\Validator;

use Contao\Database;
use Netzmacht\Contao\Toolkit\Data\Alias\Validator;

/**
 * Class UniqueDatabaseValueValidator validates a value as true if it does not exists in the database.
 *
 * @package Netzmacht\Contao\Toolkit\Data\Alias\Validator
 */
class UniqueDatabaseValueValidator implements Validator
{
    /**
     * Database connection.
     *
     * @var Database
     */
    private $database;

    /**
     * Table name.
     *
     * @var string
     */
    private $tableName;

    /**
     * Column name.
     *
     * @var string
     */
    private $columnName;

    /**
     * Unique query.
     *
     * @var string
     */
    private $query;

    /**
     * UniqueDatabaseValueValidator constructor.
     *
     * @param Database $database   Database connection.
     * @param string   $tableName  Table name.
     * @param string   $columnName Column name.
     */
    public function __construct(Database $database, $tableName, $columnName)
    {
        $this->database   = $database;
        $this->tableName  = $tableName;
        $this->columnName = $columnName;

        $this->query = sprintf(
            'SELECT count(*) AS result FROM %s WHERE %s=?',
            $this->tableName,
            $this->columnName
        );
    }

    /**
     * {@inheritDoc}
     */
    public function validate($value, array $exclude = null)
    {
        $query = $this->query;

        if ($exclude) {
            $query .= ' AND id NOT IN(?' . str_repeat(',?', (count($exclude) - 1)) . ')';
            $value  = array_merge([$value], $exclude);
        }

        $result = $this->database
            ->prepare($query)
            ->execute($value);

        return $result->result == 0;
    }
}