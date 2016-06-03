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
 * EnumType class description.
 *
 * @package    Components
 * @subpackage Exporter\FieldTypes
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class EnumType extends \Bridge\Components\Exporter\FieldTypes\AbstractFieldType
{

    /**
     * EnumType constructor.
     *
     * @param mixed $fieldLength  Field type length parameter.
     * @param mixed $defaultValue Field type default value parameter.
     */
    public function __construct($fieldLength = null, $defaultValue = null)
    {
        $this->setTypeName('enum');
        parent::__construct($fieldLength, $defaultValue);
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
        return in_array($value, $this->getFieldLength(), true);
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
        return is_array($fieldLength) === true and count($fieldLength) === count($fieldLength, COUNT_RECURSIVE);
    }
}
