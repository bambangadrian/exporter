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
 * TableEntityBuilder class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class TableEntityBuilder extends \Bridge\Components\Exporter\AbstractEntityBuilder
{

    /**
     * Constraint instance property.
     *
     * @var \Bridge\Components\Exporter\Contracts\ConstraintEntityInterface[] $Constraints
     */
    protected $Constraints = [];

    /**
     * Field constraint that will mapped to the table entity instance.
     *
     * @var array $FieldConstraintMapper
     */
    protected $FieldConstraintMapper = [];

    /**
     * TableEntityBuilder constructor.
     *
     * @param \Bridge\Components\Exporter\Contracts\DataSourceInterface $dataSourceObj Data source instance parameter.
     * @param array                                                     $constraints   Constraint entity instance
     *                                                                                 collection data parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If any error raised when construct and build the object.
     */
    public function __construct(
        \Bridge\Components\Exporter\Contracts\DataSourceInterface $dataSourceObj,
        array $constraints = []
    ) {
        try {
            parent::__construct($dataSourceObj);
            $this->setConstraints($constraints);
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Build the entities data.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If Invalid field mapper array data given.
     *
     * @return void
     */
    public function doBuild()
    {
        # Load and validate the data source and
        $this->doLoad();
        # Get the entities data.
        $entitiesData = $this->getEntitiesData();
        # Initialize the entity object content.
        $entityCollection = [];
        $dataSourceFields = $this->getDataSourceObject()->getFields();
        # Get the field constraint mapper.
        $fieldConstraintMapper = $this->getFieldConstraintMapper();
        foreach ($entitiesData as $entityName => $entityData) {
            # Create a table source as the data source for entity.
            $constraintEntityObj = $this->getConstraint($entityName);
            $entityObj = new \Bridge\Components\Exporter\TableEntity(
                $entityName,
                $this->getDataSourceObject(),
                $constraintEntityObj
            );
            # Set the fields into entity.
            $fields = (array)$dataSourceFields[$entityName];
            # Get the specific entity field constraint mapper.
            $fieldConstraintMapperData = [];
            if (array_key_exists($entityName, $fieldConstraintMapper) === true) {
                $fieldConstraintMapperData = $fieldConstraintMapper[$entityName];
            }
            try {
                foreach ($fields as $field) {
                    $fieldObj = new \Bridge\Components\Exporter\FieldElement($field);
                    if (count($fieldConstraintMapperData) > 0 and
                        ($mappedFieldKey = array_search($field, $fieldConstraintMapperData, true)) !== false
                    ) {
                        $field = $mappedFieldKey;
                    }
                    if ($constraintEntityObj instanceof \Bridge\Components\Exporter\ConstraintEntity) {
                        $fieldObj->setConstraints($constraintEntityObj->getField($field)->getConstraints());
                    }
                    $entityObj->addField($fieldObj);
                }
            } catch (\Exception $ex) {
                throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
            }
            $entityObj->setData($entityData);
            # Add the entity object to the collection.
            $entityCollection[$entityName] = $entityObj;
        }
        $this->Entities = $entityCollection;
        # Run the build entities procedure.
    }

    /**
     * Get the specific constraint instance that registered on constraints data collection.
     *
     * @param string $constraintEntityName Constraint entity name parameter.
     *
     * @return \Bridge\Components\Exporter\Contracts\ConstraintEntityInterface
     */
    public function getConstraint($constraintEntityName)
    {
        if (count($this->getConstraints()) > 0 and
            array_key_exists($constraintEntityName, $this->getConstraints()) === true
        ) {
            return $this->getConstraints()[$constraintEntityName];
        }
        return null;
    }

    /**
     * Get the constraint entities data collection property.
     *
     * @return \Bridge\Components\Exporter\Contracts\ConstraintEntityInterface[]
     */
    public function getConstraints()
    {
        return $this->Constraints;
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
     * @return \Bridge\Components\Exporter\Contracts\TableEntityInterface
     */
    public function getEntity($entityName)
    {
        $entity = parent::getEntity($entityName);
        if ($entity instanceof \Bridge\Components\Exporter\Contracts\TableEntityInterface) {
            return $entity;
        }
        throw new \Bridge\Components\Exporter\ExporterException('Invalid table entity object format');
    }

    /**
     * Get field constraint mapper data property.
     *
     * @return array
     */
    public function getFieldConstraintMapper()
    {
        return $this->FieldConstraintMapper;
    }

    /**
     * Set the entity constraint property.
     *
     * @param array $constraints Constraint entity instance collection data parameter.
     *
     * @return void
     */
    public function setConstraints(array $constraints = [])
    {
        foreach ($constraints as $constraintName => $constraintData) {
            if ($constraintData instanceof \Bridge\Components\Exporter\Contracts\ConstraintEntityInterface) {
                $this->Constraints[$constraintName] = $constraintData;
            }
        }
    }

    /**
     * Set the field constraint mapper data property.
     *
     * @param array $fieldConstraintMapper Field constraint mapper data parameter.
     *
     * @return void
     */
    public function setFieldConstraintMapper($fieldConstraintMapper)
    {
        $this->FieldConstraintMapper = $fieldConstraintMapper;
    }
}
