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
abstract class AbstractDatabaseHandler implements
    \Bridge\Components\Exporter\Contracts\DatabaseHandlerInterface,
    \Bridge\Components\Exporter\Contracts\ExporterHandlerInterface
{

    /**
     * Connection config data property.
     *
     * @var array $ConnectionConfig
     */
    protected $ConnectionConfig;

    /**
     * Constraint entity collection data.
     *
     * @var \Bridge\Components\Exporter\ConstraintEntity[] $Constraints
     */
    protected $Constraints;

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
     * Field type mapper data property.
     *
     * @var array $FieldTypeMapper
     */
    protected $FieldTypeMapper;

    /**
     * Field column lists collection data.
     *
     * @var array $Fields
     */
    protected $Fields;

    /**
     * Database server host property.
     *
     * @var string $Host
     */
    protected $Host;

    /**
     * Table collection that will be loaded.
     *
     * @var array $LoadedTables
     */
    protected $LoadedTables;

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
     * @param array $loadedTables Selected table collection data that want to be loaded.
     *
     * @return void
     */
    public function doRead(array $loadedTables = [])
    {
        if (count($loadedTables) > 0 and $loadedTables !== null) {
            $this->setLoadedTables($loadedTables);
        }
        $loadedTables = array_map(
            function ($val) {
                return strtolower($val);
            },
            $this->getLoadedTables()
        );
        $tableList = $this->getSchemaManagerObject()->listTables();
        $fields = [];
        $sourceData = [];
        # Fetch all the fields from table list.
        foreach ($tableList as $tableObj) {
            if (count($loadedTables) > 0 and
                in_array(strtolower($tableObj->getName()), $loadedTables, true) === false
            ) {
                continue;
            }
            $columnCollection = $this->getSchemaManagerObject()->listTableColumns(
                $tableObj->getName(),
                $this->getDatabaseConnectionObject()->getDatabase()
            );
            foreach ($columnCollection as $columnObj) {
                $fields[$tableObj->getName()][] = $columnObj->getName();
            }
            # Fetch all the data grouped by table.
            $queryFetchData = 'SELECT * FROM "' . $tableObj->getName() . '"';
            $tableObj->getColumns();
            $sourceData[$tableObj->getName()] = $this->getDatabaseConnectionObject()->fetchAll($queryFetchData);
        }
        # Set the tables, and fields.
        $this->Tables = $tableList;
        $this->Fields = $fields;
        # Set the source data.
        $this->Data = $sourceData;
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
     * Get loaded table property.
     *
     * @return array
     */
    public function getLoadedTables()
    {
        return $this->LoadedTables;
    }

    /**
     * Get mapped field type.
     *
     * @param string $fieldType Field type parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException Invalid field type given.
     *
     * @return string
     */
    public function getMappedFieldType($fieldType)
    {
        try {
            if ($this->validateFieldTypeMapper() === true and
                array_key_exists($fieldType, $this->getFieldTypeMapper()) === true
            ) {
                $fieldType = $this->getFieldTypeMapper()[$fieldType];
            }
            $validType = \Bridge\Components\Exporter\FieldTypes\FieldTypeFactory::$AllowedTypeList;
            if (in_array($fieldType, $validType, true) === false) {
                throw new \Bridge\Components\Exporter\ExporterException('Invalid field type given: ' . $fieldType);
            }
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
        return $fieldType;
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
     * Set loaded table property.
     *
     * @param array $loadedTables Table collection data parameter that want to be loaded.
     *
     * @return void
     */
    public function setLoadedTables($loadedTables)
    {
        $this->LoadedTables = $loadedTables;
    }

    /**
     * Load the database connection.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If any error raised when loading the doctrine refactor.
     * @throws \Bridge\Components\Exporter\ExporterException If the converted type is not found.
     *
     * @return void
     */
    protected function doLoad()
    {
        /* @noinspection PhpInternalEntityUsedInspection */
        $config = new \Doctrine\DBAL\Configuration();
        try {
            $this->DatabaseConnection = \Doctrine\DBAL\DriverManager::getConnection(
                $this->getConnectionConfig(),
                $config
            );
            # Perform to convert bit type to boolean.
            $platform = $this->getDatabaseConnectionObject()->getDatabasePlatform();
            $platform->registerDoctrineTypeMapping('bit', 'boolean');
        } catch (\Doctrine\DBAL\DBALException $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
        $this->SchemaManager = $this->getDatabaseConnectionObject()->getSchemaManager();
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

    /**
     * Validate the field type mapper property.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid field type mapper array data given.
     *
     * @return boolean
     */
    protected function validateFieldTypeMapper()
    {
        $validType = \Bridge\Components\Exporter\FieldTypes\FieldTypeFactory::$AllowedTypeList;
        if (count($this->getFieldTypeMapper()) > 0 and
            count(array_diff($this->getFieldTypeMapper(), $validType)) !== 0
        ) {
            throw new \Bridge\Components\Exporter\ExporterException('Invalid field type mapper array data given');
        }
        return true;
    }
}
