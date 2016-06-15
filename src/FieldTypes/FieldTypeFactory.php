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
 * FieldTypeFactory class description.
 *
 * @package    Components
 * @subpackage Exporter\FieldTypes
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class FieldTypeFactory implements \Bridge\Components\Exporter\Contracts\FactoryInterface
{

    /**
     * Field type constraint data mapper property.
     *
     * @var array $AllowedTypeList
     */
    public static $AllowedTypeList = ['string', 'char', 'number', 'date', 'enum', 'boolean'];

    /**
     * Create the correct field type.
     *
     * @param string $type         The field type code string parameter.
     * @param mixed  $fieldLength  The field type length parameter.
     * @param mixed  $defaultValue The field type default value parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid field type code given.
     * @throws \Bridge\Components\Exporter\ExporterException If field type class not found.
     *
     * @return \Bridge\Components\Exporter\Contracts\FieldTypeInterface
     */
    public function createType($type, $fieldLength = null, $defaultValue = null)
    {
        if (in_array($type, static::$AllowedTypeList, true) === false) {
            throw new \Bridge\Components\Exporter\ExporterException('Invalid field type code given: ' . $type);
        }
        $className = __NAMESPACE__ . '\\' . ucwords($type) . 'Type';
        if (class_exists($className) === false) {
            throw new \Bridge\Components\Exporter\ExporterException('Field type of : ' . $type . ' not found');
        }
        return new $className($fieldLength, $defaultValue);
    }

    /**
     * Get factory name property.
     *
     * @return string
     */
    public function getFactoryName()
    {
        return 'FieldsTypesFactory';
    }
}
