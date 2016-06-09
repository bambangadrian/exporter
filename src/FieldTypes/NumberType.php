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
 * NumberType class description.
 *
 * @package    Components
 * @subpackage Exporter\FieldTypes
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class NumberType extends \Bridge\Components\Exporter\FieldTypes\AbstractFieldType
{

    /**
     * Base decimal place length property
     *
     * @var integer $DecimalLength
     */
    private $DecimalLength = 11;

    /**
     * Number precision length property.
     *
     * @var integer $PrecisionLength .
     */
    private $PrecisionLength = 0;

    /**
     * NumberType constructor.
     *
     * @param mixed $fieldLength  Field type length parameter.
     * @param mixed $defaultValue Field type default value parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If any error raised when construct the object.
     */
    public function __construct($fieldLength = null, $defaultValue = null)
    {
        $this->setTypeName('number');
        parent::__construct($fieldLength, $defaultValue);
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
            parent::setFieldLength($fieldLength);
            if (is_array($fieldLength) === true) {
                $this->DecimalLength = $fieldLength[0];
                $this->PrecisionLength = $fieldLength[1];
            }
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Do validate the entity using the given constraint.
     *
     * @param mixed $value Value that will validate by the constraint data that has been provided.
     *
     * @return boolean
     */
    public function validateConstraint($value)
    {
        return is_numeric($value);
    }

    /**
     * Validate the field type length.
     *
     * @param mixed $fieldLength Field type length parameter.
     *
     * @return boolean
     */
    protected function validateFieldLength($fieldLength)
    {
        return (is_array($fieldLength) === true and count($fieldLength) === 2 and
            is_int($fieldLength[0]) === true and is_int($fieldLength[1]) === true) or
        ($this->isInteger($fieldLength) === true);
    }
}
