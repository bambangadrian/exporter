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
     * Required field array data property.
     *
     * @var array $RequiredFields
     */
    protected $RequiredFields;

    /**
     * ConstraintEntityBuilder constructor.
     *
     * @param \Bridge\Components\Exporter\Contracts\DataSourceInterface $dataSource Data source instance parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If any error raised when constructing the object.
     */
    public function __construct(\Bridge\Components\Exporter\Contracts\DataSourceInterface $dataSource)
    {
        try {
            $this->setRequiredFields(['fieldName', 'required', 'fieldType', 'fieldLength']);
            parent::__construct($dataSource);
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
            # Get the entities data.
            $entitiesData = $this->getEntitiesData();
            # Initialize the entity object content.
            $entityCollection = [];
            foreach ($entitiesData as $entityName => $entity) {
                # Create a table source as the data source for entity.
                $entityObj = new \Bridge\Components\Exporter\TableEntity($entityName);
                foreach ((array)$entity as $fieldData) {
                    # Parse the field constraint from entity array.
                    $constraints = [
                        'required'    => $fieldData['required'],
                        'fieldType'   => $this->getFieldTypeMap($fieldData['fieldType']),
                        'fieldLength' => $fieldData['fieldLength']
                    ];
                    # Create the field element and assign the field element into the table entity.
                    $fieldElement = new \Bridge\Components\Exporter\FieldElement($fieldData['fieldName'], $constraints);
                    $entityObj->addField($fieldElement);
                }
                # Add the entity object to the collection.
                $entityCollection[$entityName] = $entityObj;
            }
            $this->Entities = $entityCollection;
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
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
}
