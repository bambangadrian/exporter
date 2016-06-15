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
 * FieldElement class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class FieldElement implements \Bridge\Components\Exporter\Contracts\FieldElementInterface
{

    /**
     * Auto increment state property.
     *
     * @var boolean
     */
    private $AutoIncrement = false;

    /**
     * Constraint data array property.
     *
     * @var array $Constraints
     */
    private $Constraints;

    /**
     * Field default value property.
     *
     * @var mixed $DefaultValue
     */
    private $DefaultValue;

    /**
     * Field length property.
     *
     * @var mixed $FieldLength
     */
    private $FieldLength;

    /**
     * Field name property.
     *
     * @var string $FieldName
     */
    private $FieldName;

    /**
     * Field type property.
     *
     * @var \Bridge\Components\Exporter\Contracts\FieldTypeInterface $FieldType
     */
    private $FieldType;

    /**
     * Primary key state property.
     *
     * @var boolean
     */
    private $PrimaryKey = false;

    /**
     * Required state property.
     *
     * @var boolean
     */
    private $Required = false;

    /**
     * Valid field constraint name data array property.
     *
     * @var array $ValidFieldConstraints
     */
    protected static $ValidFieldConstraints = [
        'primaryKey'    => 'setAsPrimaryKey',
        'fieldTypeData' => 'setFieldTypeData',
        'required'      => 'setRequired',
        'foreignKey'    => 'setDependency',
        'autoIncrement' => 'setAutoIncrement'
    ];

    /**
     * FieldElement constructor.
     *
     * @param string $fieldName   Field name data parameter.
     * @param array  $constraints Constraints data array parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If any error raised when init the instance.
     */
    public function __construct(
        $fieldName,
        array $constraints = []
    ) {
        try {
            $this->setFieldName($fieldName);
            if (count($constraints) > 0) {
                $this->setConstraints($constraints);
            }
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Add constraint data to constraints property
     *
     * @param string $constraintName  Constraint name parameter.
     * @param mixed  $constraintValue Constraint value parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid constraint name or value given.
     *
     * @return void
     */
    public function addConstraint($constraintName, $constraintValue)
    {
        if (array_key_exists($constraintName, static::$ValidFieldConstraints) === false) {
            throw new \Bridge\Components\Exporter\ExporterException(
                'Invalid constraint name given: ' . $constraintName
            );
        }
        $callableMethod = static::$ValidFieldConstraints[$constraintName];
        if (method_exists($this, $callableMethod) === true) {
            $this->{$callableMethod}($constraintValue);
        }
    }

    /**
     * Get data constraints array property.
     *
     * @return array
     */
    public function getConstraints()
    {
        return $this->Constraints;
    }

    /**
     * Get field default value property.
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->getFieldTypeObject()->getDefaultValue();
    }

    /**
     * Get the field length property.
     *
     * @return mixed
     */
    public function getFieldLength()
    {
        return $this->getFieldTypeObject()->getFieldLength();
    }

    /**
     * Get the field name property
     *
     * @return string
     */
    public function getFieldName()
    {
        return $this->FieldName;
    }

    /**
     * Get the field type name property.
     *
     * @return string
     */
    public function getFieldType()
    {
        return $this->getFieldTypeObject()->getTypeName();
    }

    /**
     * Get the field type instance property.
     *
     * @return \Bridge\Components\Exporter\Contracts\FieldTypeInterface
     */
    public function getFieldTypeObject()
    {
        return $this->FieldType;
    }

    /**
     * Get auto increment state property
     *
     * @return boolean
     */
    public function isAutoIncrement()
    {
        return $this->AutoIncrement;
    }

    /**
     * Get primary key state property.
     *
     * @return boolean
     */
    public function isPrimaryKey()
    {
        return $this->PrimaryKey;
    }

    /**
     * Get the field required constraint.
     *
     * @return boolean
     */
    public function isRequired()
    {
        return $this->Required;
    }

    /**
     * Set field as primary key.
     *
     * @param boolean $isPrimaryKey Primary key option parameter.
     *
     * @return void
     */
    public function setAsPrimaryKey($isPrimaryKey = true)
    {
        $this->Constraints['primaryKey'] = (boolean)$isPrimaryKey;
        $this->PrimaryKey = (boolean)$isPrimaryKey;
    }

    /**
     * Set field is a auto increment.
     *
     * @param boolean $isAutoIncrement Auto increment option parameter.
     *
     * @return void
     */
    public function setAutoIncrement($isAutoIncrement = true)
    {
        $this->Constraints['autoIncrement'] = (boolean)$isAutoIncrement;
        $this->AutoIncrement = (boolean)$isAutoIncrement;
    }

    /**
     * Set the constraints data array property.
     *
     * @param array $constraints Constraints data array parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid constraints data given.
     *
     * @return void
     */
    public function setConstraints(array $constraints)
    {
        try {
            foreach ($constraints as $constraintName => $constraintValue) {
                $this->addConstraint($constraintName, $constraintValue);
            }
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Set field dependency.
     *
     * @param \Bridge\Components\Exporter\FieldElement $dependencyFieldName Dependency field name parameter.
     *
     * @return void
     */
    public function setDependency(\Bridge\Components\Exporter\FieldElement $dependencyFieldName)
    {
        $this->Constraints['foreignKey'] = $dependencyFieldName;
    }

    /**
     * Set field name property.
     *
     * @param string $fieldName Field name parameter.
     *
     * @return void
     */
    public function setFieldName($fieldName)
    {
        $this->FieldName = $fieldName;
    }

    /**
     * Process and setting all the field type data.
     *
     * @param array $fieldTypeData Field type data parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If any errors raised when set the field type.
     *
     * @return void
     */
    public function setFieldTypeData(array $fieldTypeData)
    {
        $fieldTypeDataTemplate = ['type' => null, 'length' => null, 'default' => null];
        $fieldTypeData = array_merge($fieldTypeDataTemplate, $fieldTypeData);
        $fieldTypeFactory = new \Bridge\Components\Exporter\FieldTypes\FieldTypeFactory();
        try {
            $fieldTypeObject = $fieldTypeFactory->createType(
                $fieldTypeData['type'],
                $fieldTypeData['length'],
                $fieldTypeData['default']
            );
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException('Failed to create type: ' . $ex->getMessage());
        }
        if ($fieldTypeObject instanceof \Bridge\Components\Exporter\FieldTypes\DateType and
            array_key_exists('format', $fieldTypeData) === true
        ) {
            $fieldTypeObject->setDateFormat($fieldTypeData['format']);
        }
        $this->Constraints['fieldTypeData'] = $fieldTypeData;
        $this->FieldType = $fieldTypeObject;
        $this->FieldLength = $fieldTypeData['length'];
        $this->DefaultValue = $fieldTypeData['default'];
    }

    /**
     * Set field is required (mandatory) or not.
     *
     * @param boolean $isRequired Required option parameter.
     *
     * @return void
     */
    public function setRequired($isRequired = true)
    {
        $this->Constraints['required'] = (boolean)$isRequired;
        $this->Required = (boolean)$isRequired;
    }
}
