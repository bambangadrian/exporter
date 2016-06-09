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
 * PostgreSqlHandler class description.
 *
 * @package    Components
 * @subpackage Exporter\Database
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class PostgreSqlHandler extends \Bridge\Components\Exporter\Database\AbstractDatabaseHandler
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
            $this->DatabaseDriver = 'pdo_pgsql';
            parent::__construct($connectionConfig);
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }
}
