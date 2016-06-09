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
 * AbstractDatabaseHandler class description.
 *
 * @package    Components
 * @subpackage Exporter\Database
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
abstract class AbstractDatabaseHandler implements \Bridge\Components\Exporter\Contracts\DatabaseHandlerInterface
{

    /**
     * Connection config data property.
     *
     * @var array $ConnectionConfig
     */
    protected $ConnectionConfig;

    /**
     * Array data collection parameter.
     *
     * @var array $Data
     */
    protected $Data;

    /**
     * Doctrine database connection instance.
     *
     * @var \Doctrine\DBAL\Connection $DatabaseConnection
     */
    protected $DatabaseConnection;

    /**
     * Get database driver name property.
     *
     * @var string $DatabaseDriver
     */
    protected $DatabaseDriver;

    /**
     * Database name property.
     *
     * @var string $DatabaseName
     */
    protected $DatabaseName;

    /**
     * Field column lists collection data.
     *
     * @var \Doctrine\DBAL\Schema\Column[] $Fields
     */
    protected $Fields;

    /**
     * Database server host property.
     *
     * @var string $Host
     */
    protected $Host;

    /**
     * Doctrine schema manager instance.
     *
     * @var \Doctrine\DBAL\Schema\AbstractSchemaManager $SchemaManager
     */
    protected $SchemaManager;

    /**
     * Tables collection data.
     *
     * @var \Doctrine\DBAL\Schema\Table[] $Tables
     */
    protected $Tables;

    /**
     * Database user property.
     *
     * @var string $User
     */
    protected $User;

    /**
     * AbstractDatabaseHandler constructor.
     *
     * @param array $connectionConfig Connection configuration data array parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If database connection failed.
     */
    public function __construct(array $connectionConfig)
    {
        try {
            $this->setConnectionConfig($connectionConfig);
            $this->doLoad();
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Fetch all data information from database.
     *
     * @return void
     */
    public function doRead()
    {
        $tables = $this->getTables();
        foreach ($tables as $table) {
            $queryFetchData = 'SELECT * FROM "' . $table->getName() . '"';
            $table->getColumns();
            $this->Data[$table->getName()] = $this->getDatabaseConnectionObject()->fetchAll($queryFetchData);
        }
    }

    /**
     * Execute query string.
     *
     * @param string $query Query string parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If failed to execute given query.
     *
     * @return \Doctrine\DBAL\Driver\Statement
     */
    public function executeQuery($query)
    {
        try {
            return $this->DatabaseConnection->query($query);
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException(
                'Failed to execute query: ' . $query . ' - ' . $ex->getMessage()
            );
        }
    }

    /**
     * Get connection config data property.
     *
     * @return array
     */
    public function getConnectionConfig()
    {
        return $this->ConnectionConfig;
    }

    /**
     * Get connection config item data property.
     *
     * @param string $itemName Configuration item name parameter.
     *
     * @return string
     */
    public function getConnectionConfigItem($itemName)
    {
        if (array_key_exists($itemName, $this->getConnectionConfig()) === true) {
            return $this->getConnectionConfig()[$itemName];
        }
        return null;
    }

    /**
     * Get data array property.
     *
     * @param string $tableName Table name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If table name not found on the data collection.
     *
     * @return array
     */
    public function getData($tableName = '')
    {
        if ($tableName !== null and trim($tableName) !== '') {
            if (array_key_exists($tableName, $this->Data) === true) {
                return $this->Data[$tableName];
            } else {
                throw new \Bridge\Components\Exporter\ExporterException('Table name not found: ' . $tableName);
            }
        }
        return $this->Data;
    }

    /**
     * Get doctrine database connection object.
     *
     * @return \Doctrine\DBAL\Connection
     */
    public function getDatabaseConnectionObject()
    {
        return $this->DatabaseConnection;
    }

    /**
     * Get database driver name.
     *
     * @return string
     */
    public function getDatabaseDriver()
    {
        return $this->DatabaseDriver;
    }

    /**
     * Get database name property.
     *
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->DatabaseName;
    }

    /**
     * Get all field list or only on selected table.
     *
     * @param string $tableName Table name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If table name that given was not found on field collection.
     *
     * @return array
     */
    public function getFields($tableName = '')
    {
        if (trim($tableName) !== '' and $tableName !== null) {
            if (array_key_exists($tableName, $this->getTables()) === true) {
                return $this->Fields[$tableName];
            } else {
                throw new \Bridge\Components\Exporter\ExporterException('Table name was not found: ' . $tableName);
            }
        }
        return $this->Fields;
    }

    /**
     * Get database server host property.
     *
     * @return string
     */
    public function getHost()
    {
        return $this->Host;
    }

    /**
     * Get database schema manager object.
     *
     * @return \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    public function getSchemaManagerObject()
    {
        return $this->SchemaManager;
    }

    /**
     * Get all table list on database.
     *
     * @return \Doctrine\DBAL\Schema\Table[]
     */
    public function getTables()
    {
        return $this->Tables;
    }

    /**
     * Get database user property.
     *
     * @return string
     */
    public function getUser()
    {
        return $this->User;
    }

    /**
     * Load the database connection.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If any error raised when loading the doctrine refactor.
     *
     * @return void
     */
    protected function doLoad()
    {
        try {
            /* @noinspection PhpInternalEntityUsedInspection */
            $config = new \Doctrine\DBAL\Configuration();
            $this->DatabaseConnection = \Doctrine\DBAL\DriverManager::getConnection(
                $this->getConnectionConfig(),
                $config
            );
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
        $this->SchemaManager = $this->getDatabaseConnectionObject()->getSchemaManager();
        $this->Tables = $this->getSchemaManagerObject()->listTables();
    }

    /**
     * Set connection config data property.
     *
     * @param array $connectionConfig Connection configuration data parameter.
     *
     * @return void
     */
    protected function setConnectionConfig(array $connectionConfig)
    {
        if (array_key_exists('host', $connectionConfig) === true) {
            $this->Host = $connectionConfig['host'];
        }
        if (array_key_exists('dbname', $connectionConfig) === true) {
            $this->DatabaseName = $connectionConfig['dbname'];
        }
        if (array_key_exists('user', $connectionConfig) === true) {
            $this->User = $connectionConfig['user'];
        }
        # Add the driver item data into array.
        $connectionConfig['driver'] = $this->getDatabaseDriver();
        $this->ConnectionConfig = $connectionConfig;
    }
}
