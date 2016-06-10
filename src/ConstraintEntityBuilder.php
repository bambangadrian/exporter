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
 * ConstraintEntityBuilder class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class ConstraintEntityBuilder extends \Bridge\Components\Exporter\AbstractEntityBuilder
{

    /**
     * Date format that applied on the entities collection.
     *
     * @var string $DateFormat
     */
    protected $DateFormat = 'Y-m-d';

    /**
     * Field type mapper data property.
     *
     * @var array
     */
    protected $FieldTypeMapper = [];

    /**
     * Required field array data property.
     *
     * @var array $RequiredFields
     */
    protected $RequiredFields;

    /**
     * ConstraintEntityBuilder constructor.
     *
     * @param \Bridge\Components\Exporter\Contracts\DataSourceInterface $dataSourceObj Data source instance parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If any error raised when constructing the object.
     */
    public function __construct(\Bridge\Components\Exporter\Contracts\DataSourceInterface $dataSourceObj)
    {
        try {
            $this->setRequiredFields(['fieldName', 'required', 'fieldType', 'fieldLength']);
            parent::__construct($dataSourceObj);
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Load initialization of data source entities builder.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If Invalid field mapper array data given.
     *
     * @return void
     */
    public function doBuild()
    {
        # Run the build entities procedure.
        try {
            # Load and validate the data source and
            $this->doLoad();
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
        # Get the entities data.
        $entitiesData = $this->getEntitiesData();
        # Initialize the entity object content.
        $entityCollection = [];
        foreach ($entitiesData as $entityName => $entityData) {
            # Create a table source as the data source for entity.
            $entityObj = new \Bridge\Components\Exporter\ConstraintEntity($entityName, $this->getDataSourceObject());
            $entityObj->setData($entityData);
            foreach ((array)$entityData as $fieldData) {
                $fieldType = $this->getMappedFieldType($fieldData['fieldType']);
                $fieldLength = $fieldData['fieldLength'];
                if ($this->getDataSourceType() === 'excel' and $fieldType === 'enum') {
                    $fieldLength = json_decode(
                        \Bridge\Components\Exporter\StringUtility::toJson($fieldLength),
                        true
                    );
                }
                # Parse the field constraint from entity array.
                $constraints = [
                    'required'      => (boolean)$fieldData['required'],
                    'fieldTypeData' => [
                        'type'   => $fieldType,
                        'length' => $fieldLength
                    ]
                ];
                if ($fieldType === 'date') {
                    $dateFormat = $this->getDateFormat();
                    if (array_key_exists('fieldFormat', $fieldData) === true) {
                        $dateFormat = $fieldData['fieldFormat'];
                    }
                    $constraints['fieldTypeData']['format'] = $dateFormat;
                }
                # Create the field element object and assign the field element into the table entity.
                $fieldObj = new \Bridge\Components\Exporter\FieldElement($fieldData['fieldName'], $constraints);
                $entityObj->addField($fieldObj);
            }
            # Add the entity object to the collection.
            $entityCollection[$entityName] = $entityObj;
        }
        $this->Entities = $entityCollection;
    }

    /**
     * Get date format property.
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->DateFormat;
    }

    /**
     * Get entity object.
     *
     * @param string $entityName Entity name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If entity name not found on collections.
     * @throws \Bridge\Components\Exporter\ExporterException If invalid entity name given.
     * @throws \Bridge\Components\Exporter\ExporterException If invalid table entity object format found.
     *
     * @return \Bridge\Components\Exporter\Contracts\ConstraintEntityInterface
     */
    public function getEntity($entityName)
    {
        $entity = parent::getEntity($entityName);
        if ($entity instanceof \Bridge\Components\Exporter\Contracts\ConstraintEntityInterface === false) {
            throw new \Bridge\Components\Exporter\ExporterException('Invalid table entity object format');
        }
        return $entity;
    }

    /**
     * Get the field type mapper data property.
     *
     * @return array
     */
    public function getFieldTypeMapper()
    {
        return $this->FieldTypeMapper;
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
            $validType = \Bridge\Components\Exporter\FieldTypes\FieldTypesFactory::$AllowedTypeList;
            if (in_array($fieldType, $validType, true) === false) {
                throw new \Bridge\Components\Exporter\ExporterException('Invalid field type given: ' . $fieldType);
            }
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
        return $fieldType;
    }

    /**
     * Get required fields data property.
     *
     * @return array
     */
    public function getRequiredFields()
    {
        return $this->RequiredFields;
    }

    /**
     * Set date format property.
     *
     * @param string $dateFormat Date format parameter.
     *
     * @return void
     */
    public function setDateFormat($dateFormat = 'Y-m-d')
    {
        $this->DateFormat = $dateFormat;
    }

    /**
     * Set field mapper data property.
     *
     * @param array $fieldMapper Field mapper data parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid field mapper array data given.
     *
     * @return void
     */
    public function setFieldMapper(array $fieldMapper = [])
    {
        $this->FieldMapper = $fieldMapper;
    }

    /**
     * Set field type mapper data property.
     *
     * @param array $fieldTypeMapper Field type mapper data parameter.
     *
     * @return void
     */
    public function setFieldTypeMapper(array $fieldTypeMapper = [])
    {
        $this->FieldTypeMapper = $fieldTypeMapper;
    }

    /**
     * Set the required fields data property.
     *
     * @param array $requiredFields Required fields data parameter.
     *
     * @return void
     */
    public function setRequiredFields(array $requiredFields)
    {
        $this->RequiredFields = $requiredFields;
    }

    /**
     * Validate the data source property.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If one of required field does not exists.
     *
     * @return void
     */
    protected function doLoad()
    {
        parent::doLoad();
        # Check the required fields.
        $tempFieldMapper = [];
        $fieldMapper = $this->getFieldMapper();
        $requiredFields = $this->getRequiredFields();
        $dataSourceFields = $this->getDataSourceObject()->getFields();
        $dataSourceFields = array_pop($dataSourceFields);
        foreach ($dataSourceFields as $field) {
            if (in_array($field, $fieldMapper, true) === true) {
                $tempFieldMapper[] = array_search($field, $fieldMapper, true);
                continue;
            }
            $tempFieldMapper[] = $field;
        }
        # Validate the all the required fields exists on the data source.
        if (count(array_diff($requiredFields, $tempFieldMapper)) !== 0) {
            throw new \Bridge\Components\Exporter\ExporterException('Please ensure all the required fields exists');
        }
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
        # Validate the field mapper by comparing the array keys with the required constraint entity fields.
        if (parent::validateFieldMapper() === true and
            count(array_diff(array_keys($this->getFieldMapper()), $this->getRequiredFields())) !== 0
        ) {
            throw new \Bridge\Components\Exporter\ExporterException('Invalid field mapper array data given');
        }
        return true;
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
        $validType = \Bridge\Components\Exporter\FieldTypes\FieldTypesFactory::$AllowedTypeList;
        if (count($this->getFieldTypeMapper()) > 0 and
            count(array_diff($this->getFieldTypeMapper(), $validType)) !== 0
        ) {
            throw new \Bridge\Components\Exporter\ExporterException('Invalid field type mapper array data given');
        }
        return true;
    }
}
