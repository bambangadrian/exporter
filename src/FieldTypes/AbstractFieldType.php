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
namespace Bridge\Components\Exporter\FieldTypes;

/**
 * AbstractFieldType class description.
 *
 * @package    Components
 * @subpackage Exporter\FieldTypes
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
abstract class AbstractFieldType implements \Bridge\Components\Exporter\Contracts\FieldTypeInterface
{

    /**
     * Default value property.
     *
     * @var mixed $DefaultValue
     */
    protected $DefaultValue;

    /**
     * Field type length property.
     *
     * @var mixed $FieldLength
     */
    protected $FieldLength;

    /**
     * Field type name property.
     *
     * @var string $FieldName
     */
    protected $TypeName;

    /**
     * AbstractFieldType constructor.
     *
     * @param mixed $fieldLength  Field type length parameter.
     * @param mixed $defaultValue Field type default value parameter.
     */
    public function __construct($fieldLength = null, $defaultValue = null)
    {
        $this->setFieldLength($fieldLength);
        if ($defaultValue !== null) {
            $this->setDefaultValue($defaultValue);
        }
    }

    /**
     * Format the value that passed by the type algorithm.
     *
     * @param mixed $value Value that want to be formatted.
     *
     * @return mixed
     */
    public function doFormat($value)
    {
        return $value;
    }

    /**
     * Get default value property.
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->DefaultValue;
    }

    /**
     * Get the field type length.
     *
     * @return mixed
     */
    public function getFieldLength()
    {
        return $this->FieldLength;
    }

    /**
     * Get the type name property.
     *
     * @return string
     */
    public function getTypeName()
    {
        return $this->TypeName;
    }

    /**
     * Set the field type length property.
     *
     * @param mixed $fieldLength Field type length parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid field length given.
     *
     * @return void
     */
    public function setFieldLength($fieldLength)
    {
        try {
            if ($this->validateFieldLength($fieldLength) === true) {
                $this->FieldLength = $fieldLength;
            }
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Check if value is an integer type.
     *
     * @param mixed $value Value that want to be checked.
     *
     * @return boolean
     */
    protected function isInteger($value)
    {
        return is_numeric($value) and (string)(integer)$value === (string)$value;
    }

    /**
     * Set default value property.
     *
     * @param mixed $value Default value parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid default value given.
     *
     * @return void
     */
    protected function setDefaultValue($value)
    {
        try {
            if ($this->validateConstraint($value) === false) {
                throw new \Bridge\Components\Exporter\ExporterException('Invalid default value given');
            }
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Set the field type name property.
     *
     * @param string $typeName Field type name parameter.
     *
     * @return void
     */
    protected function setTypeName($typeName)
    {
        $this->TypeName = $typeName;
    }

    /**
     * Validate the field type length.
     *
     * @param mixed $fieldLength Field type length parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid field length given.
     *
     * @return boolean
     */
    abstract protected function validateFieldLength($fieldLength);
}
