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
namespace Bridge\Components\Exporter\Contracts;

/**
 * DatabaseHandlerInterface class description.
 *
 * @package    Components
 * @subpackage Exporter\Contracts
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
interface DatabaseHandlerInterface
{

    /**
     * Fetch all data information from database.
     *
     * @return void
     */
    public function doRead();

    /**
     * Execute query string.
     *
     * @param string $query Query string parameter.
     *
     * @return \Doctrine\DBAL\Driver\Statement
     */
    public function executeQuery($query);

    /**
     * Get connection config data property.
     *
     * @return array
     */
    public function getConnectionConfig();

    /**
     * Get data array property.
     *
     * @param string $tableName Table name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If table name not found on the data collection.
     *
     * @return array
     */
    public function getData($tableName = '');

    /**
     * Get database driver name.
     *
     * @return string
     */
    public function getDatabaseDriver();

    /**
     * Get all field list or only on selected table.
     *
     * @param string $tableName Table name parameter.
     *
     * @return array
     */
    public function getFields($tableName = '');

    /**
     * Get database schema manager object.
     *
     * @return \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    public function getSchemaManagerObject();

    /**
     * Get all table list on database.
     *
     * @return \Doctrine\DBAL\Schema\Table[]
     */
    public function getTables();
}
