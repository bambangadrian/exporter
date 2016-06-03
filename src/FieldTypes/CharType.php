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
 * CharType class description.
 *
 * @package    Components
 * @subpackage Exporter\FieldTypes
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class CharType extends \Bridge\Components\Exporter\FieldTypes\AbstractFieldType
{

    /**
     * CharType constructor.
     *
     * @param mixed $fieldLength  Field type length parameter.
     * @param mixed $defaultValue Field type default value parameter.
     */
    public function __construct($fieldLength = 11, $defaultValue = null)
    {
        $this->setTypeName('char');
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
        return strlen($value) <= $this->getFieldLength();
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
        return $this->isInteger($fieldLength);
    }
}
