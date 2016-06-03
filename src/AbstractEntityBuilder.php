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
namespace Bridge\Components\Exporter;

/**
 * AbstractEntityBuilder class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
abstract class AbstractEntityBuilder implements \Bridge\Components\Exporter\Contracts\EntityBuilderInterface
{

    /**
     * Data source instance property.
     *
     * @var \Bridge\Components\Exporter\Contracts\DataSourceInterface $DataSource
     */
    protected $DataSource;

    /**
     * Data source type property.
     *
     * @var string $DataSourceType
     */
    protected $DataSourceType;

    /**
     * Data source entity collection property.
     *
     * @var array $Entities
     */
    protected $Entities;

    /**
     * Property that handle all the data contains on data source instance property.
     *
     * @var array $EntitiesData
     */
    protected $EntitiesData = [];

    /**
     * Field mapper data property.
     *
     * @var array
     */
    protected $FieldMapper = [];

    /**
     * List of valid data source for entity builder.
     *
     * @var array $ValidDataSource
     */
    protected static $ValidDataSource = [
        'excel'    => '\Bridge\Components\Exporter\ExcelDataSource',
        'database' => '\Bridge\Components\Exporter\DbDataSource'
    ];

    /**
     * AbstractEntityBuilder constructor.
     *
     * @param \Bridge\Components\Exporter\Contracts\DataSourceInterface $dataSource Data source instance parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If any error raised when construct the instance.
     */
    public function __construct(\Bridge\Components\Exporter\Contracts\DataSourceInterface $dataSource)
    {
        try {
            # Set the data source property.
            $this->DataSource = $dataSource;
            $this->getDataSourceObject()->doLoad();
            $this->EntitiesData = $this->getDataSourceObject()->getData();
            $this->fetchDataSourceType();
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Get data source instance.
     *
     * @return \Bridge\Components\Exporter\Contracts\DataSourceInterface
     */
    public function getDataSourceObject()
    {
        return $this->DataSource;
    }

    /**
     * Get data source type property.
     *
     * @return string
     */
    public function getDataSourceType()
    {
        return $this->DataSourceType;
    }

    /**
     * Get entity collections.
     *
     * @return array
     */
    public function getEntities()
    {
        return $this->Entities;
    }

    /**
     * Entities data array property.
     *
     * @return array
     */
    public function getEntitiesData()
    {
        return $this->EntitiesData;
    }

    /**
     * Get entity object.
     *
     * @param string $entityName Entity name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If entity name not found on collections.
     * @throws \Bridge\Components\Exporter\ExporterException If invalid entity name given.
     *
     * @return \Bridge\Components\Exporter\Contracts\EntityInterface
     */
    public function getEntity($entityName)
    {
        if (array_key_exists($entityName, $this->getEntities()) === false) {
            throw new \Bridge\Components\Exporter\ExporterException('Entity not found on collections');
        }
        if (trim($entityName) === '' or $entityName === null) {
            throw new \Bridge\Components\Exporter\ExporterException('Please provide the entity name');
        }
        return $this->getEntities()[$entityName];
    }

    /**
     * Get the field mapper data property.
     *
     * @return array
     */
    public function getFieldMapper()
    {
        return $this->FieldMapper;
    }

    /**
     * Do field mapping based on the given field mapper data array property.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If field mapping failed.
     *
     * @return void
     */
    protected function doFieldMapping()
    {
        try {
            # Get the entities data.
            $entitiesData = $this->getEntitiesData();
            # Map the entities data using the valid mapper.
            $fieldMapper = $this->getFieldMapper();
            foreach ($entitiesData as $entityName => $entity) {
                foreach ((array)$entity as $rowNumber => $rows) {
                    foreach ((array)$rows as $field => $value) {
                        if (in_array($field, $fieldMapper, true) === true) {
                            unset($entitiesData[$entityName][$rowNumber][$field]);
                            $entitiesData[$entityName][$rowNumber][array_search(
                                $field,
                                $fieldMapper,
                                true
                            )] = $value;
                        }
                    }
                }
            }
            # Set the entities data.
            $this->EntitiesData = $entitiesData;
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException('Field mapping failed: ' . $ex->getMessage());
        }
    }

    /**
     * Validate the data source property.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid data source (null object) given.
     *
     * @return void
     */
    protected function doLoad()
    {
        try {
            $entitiesData = $this->getEntitiesData();
            if ($entitiesData === null or count($entitiesData) === 0) {
                throw new \Bridge\Components\Exporter\ExporterException('Invalid data source, null object given');
            }
            # Do field mapping first if the field mapper property has been defined.
            # First validate the field data mapper if set.
            if ($this->validateFieldMapper() === true) {
                $this->doFieldMapping();
            }
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Set entities data property.
     *
     * @param array $entitiesData Entities data parameter.
     *
     * @return void
     */
    protected function setEntitiesData(array $entitiesData)
    {
        $this->EntitiesData = $entitiesData;
    }

    /**
     * Validate the field mapper property.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid field mapper array data given.
     *
     * @return boolean
     */
    protected function validateFieldMapper()
    {
        # Validate the field mapper by comparing all the value are exists on the data source fields.
        $dataSourceFields = $this->getDataSourceObject()->getFields();
        $dataSourceFields = array_pop($dataSourceFields);
        if (count($this->getFieldMapper()) > 0 and
            count(array_diff($this->getFieldMapper(), $dataSourceFields)) !== 0
        ) {
            throw new \Bridge\Components\Exporter\ExporterException('Invalid field mapper array data given');
        }
        return true;
    }

    /**
     * Fetch data source type from valid data source array property.
     *
     * @return void
     */
    private function fetchDataSourceType()
    {
        foreach (static::$ValidDataSource as $dataSourceType => $validDataSource) {
            if ($this->getDataSourceObject() instanceof $validDataSource) {
                $this->DataSourceType = $dataSourceType;
            }
        }
    }
}
