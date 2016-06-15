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
 * DateType class description.
 *
 * @package    Components
 * @subpackage Exporter\FieldTypes
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class DateType extends \Bridge\Components\Exporter\FieldTypes\AbstractFieldType
{

    /**
     * Date format that applied on the entity.
     *
     * @var string $DateFormat
     */
    private $DateFormat;

    /**
     * DateType constructor.
     *
     * @param mixed $fieldLength  Field type length parameter.
     * @param mixed $defaultValue Field type default value parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If any error raised when construct the object.
     */
    public function __construct($fieldLength = null, $defaultValue = null)
    {
        $this->setTypeName('date');
        parent::__construct($fieldLength, $defaultValue);
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
     * Set date format property.
     *
     * @param string $dateFormat Date format parameter.
     *
     * @return void
     */
    public function setDateFormat($dateFormat)
    {
        $this->DateFormat = $dateFormat;
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
        $dateTimeObj = \DateTime::createFromFormat($this->getDateFormat(), $value);
        $errors = \DateTime::getLastErrors();
        return count($errors['warning_count']) === 0 and $dateTimeObj !== false;
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
        return $fieldLength === null or $fieldLength === '';
    }
}
