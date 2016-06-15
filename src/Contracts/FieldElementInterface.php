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
namespace Bridge\Components\Exporter\Contracts;

/**
 * FieldElementInterface class description.
 *
 * @package    Components
 * @subpackage Exporter\Contracts
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
interface FieldElementInterface
{

    /**
     * Get data constraints array property.
     *
     * @return array
     */
    public function getConstraints();

    /**
     * Get the field name property.
     *
     * @return string
     */
    public function getFieldName();

    /**
     * Get the field type instance property.
     *
     * @return \Bridge\Components\Exporter\Contracts\FieldTypeInterface
     */
    public function getFieldTypeObject();

    /**
     * Get auto increment state property
     *
     * @return boolean
     */
    public function isAutoIncrement();

    /**
     * Get primary key state property.
     *
     * @return boolean
     */
    public function isPrimaryKey();

    /**
     * Get required state property.
     *
     * @return boolean
     */
    public function isRequired();
}
