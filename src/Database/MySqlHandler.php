<?php
/**
 * Code written is strictly used within this program.
 * Any other use of this code is in violation of copy rights.
 *
 * @package   Components
 * @author    Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright 2016 Developer
 * @license   - No License
 * @version   GIT: $Id$
 * @link      -
 */
namespace Bridge\Components\Exporter\Database;

/**
 * MySqlHandler class description.
 *
 * @package    Components
 * @subpackage Exporter\Database
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class MySqlHandler extends \Bridge\Components\Exporter\Database\AbstractDatabaseHandler
{

    /**
     * PostgreSqlHandler constructor.
     *
     * @param array $connectionConfig Connection configuration data array parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If any error raised when construct the instance.
     */
    public function __construct(array $connectionConfig)
    {
        try {
            $this->DatabaseDriver = 'mysqli';
            parent::__construct($connectionConfig);
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Get field type mapper data property.
     *
     * @return array
     */
    public function getFieldTypeMapper()
    {
        return [
            'integer'  => 'number',
            'text'     => 'string',
            'float'    => 'number',
            'bigint'   => 'number',
            'smallint' => 'number',
            'decimal'  => 'number',
            'datetime' => 'date',
            'time'     => 'string'
        ];
    }
}
