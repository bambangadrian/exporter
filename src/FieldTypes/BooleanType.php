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
 * BooleanType class description.
 *
 * @package    Components
 * @subpackage Exporter\FieldTypes
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class BooleanType extends \Bridge\Components\Exporter\FieldTypes\AbstractFieldType
{

    /**
     * BooleanType constructor.
     *
     * @param mixed $fieldLength  Field type length parameter.
     * @param mixed $defaultValue Field type default value parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If any error raised when construct the object.
     */
    public function __construct($fieldLength = null, $defaultValue = null)
    {
        $this->setTypeName('boolean');
        parent::__construct($fieldLength, $defaultValue);
    }

    /**
     * Format the value that passed by the type algorithm.
     *
     * @param mixed $value Value that want to be formatted.
     *
     * @return boolean
     */
    public function doFormat($value)
    {
        return parent::doFormat((boolean)$value);
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
        return is_bool($value) === true;
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
        return $fieldLength === null;
    }
}
