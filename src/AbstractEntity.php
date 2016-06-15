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
 * AbstractEntity class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
abstract class AbstractEntity implements \Bridge\Components\Exporter\Contracts\EntityInterface
{

    /**
     * Entity data array property.
     *
     * @var array $Data
     */
    protected $Data;

    /**
     * Data source instance that own the entity.
     *
     * @var \Bridge\Components\Exporter\Contracts\DataSourceInterface $DataSource
     */
    protected $DataSource;

    /**
     * Fields data property.
     *
     * @var \Bridge\Components\Exporter\Contracts\FieldElementInterface[] $Fields
     */
    protected $Fields;

    /**
     * Entity name property.
     *
     * @var string $Name
     */
    protected $Name;

    /**
     * AbstractEntity constructor.
     *
     * @param string                                                    $entityName    Entity name parameter.
     * @param \Bridge\Components\Exporter\Contracts\DataSourceInterface $dataSourceObj Data source instance parameter.
     */
    public function __construct($entityName, \Bridge\Components\Exporter\Contracts\DataSourceInterface $dataSourceObj)
    {
        $this->setName($entityName);
        $this->DataSource = $dataSourceObj;
    }

    /**
     * Add field element object into fields collection property
     *
     * @param \Bridge\Components\Exporter\Contracts\FieldElementInterface $fieldElementObject Field element object
     *                                                                                        parameter.
     *
     * @return void
     */
    public function addField(\Bridge\Components\Exporter\Contracts\FieldElementInterface $fieldElementObject)
    {
        $this->Fields[$fieldElementObject->getFieldName()] = $fieldElementObject;
    }

    /**
     * Get table entity data property.
     *
     * @param array $fieldFilters Field filters data that will be retrieved from the entity data.
     *
     * @return array
     */
    public function getData(array $fieldFilters = [])
    {
        $entityData = $this->Data;
        if (count($fieldFilters) > 0) {
            foreach ($entityData as $rowNumber => $rowData) {
                # Get all field keys for each row.
                $fieldNames = array_keys($rowData);
                foreach ($fieldNames as $fieldName) {
                    # Filter the field.
                    if (in_array($fieldName, $fieldFilters, true) === false) {
                        unset($entityData[$rowNumber][$fieldName]);
                    }
                }
            }
        }
        return $entityData;
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
     * Get entity handle instance.
     *
     * @return \Bridge\Components\Exporter\Contracts\DataSourceHandlerInterface
     */
    public function getEntityHandler()
    {
        return $this->getDataSourceObject()->getDataSourceHandler();
    }

    /**
     * Get selected field property.
     *
     * @param string $fieldName Field name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If field name not found on the entity.
     *
     * @return \Bridge\Components\Exporter\Contracts\FieldElementInterface
     */
    public function getField($fieldName)
    {
        if (array_key_exists($fieldName, $this->Fields) === true) {
            return $this->Fields[$fieldName];
        }
        throw new \Bridge\Components\Exporter\ExporterException('Field name not found on the entity: ' . $fieldName);
    }

    /**
     * Get fields collection information.
     *
     * @return \Bridge\Components\Exporter\Contracts\FieldElementInterface[]
     */
    public function getFields()
    {
        return $this->Fields;
    }

    /**
     * Get entity name property.
     *
     * @return string
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * Set the entity data array property.
     *
     * @param array $data Array data of entity parameter.
     *
     * @return void
     */
    public function setData(array $data = [])
    {
        $this->Data = $data;
    }

    /**
     * Set fields collection property.
     *
     * @param array $fields Field elements data array collection parameter.
     *
     * @return void
     */
    public function setFields(array $fields)
    {
        foreach ($fields as $field) {
            if ($fields instanceof \Bridge\Components\Exporter\Contracts\FieldElementInterface) {
                $this->addField($field);
            }
        }
    }

    /**
     * Set entity name property.
     *
     * @param string $entityName Entity name parameter.
     *
     * @return void
     */
    public function setName($entityName)
    {
        $this->Name = $entityName;
    }
}
