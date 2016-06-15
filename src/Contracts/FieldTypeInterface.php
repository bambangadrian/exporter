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
 * FieldTypeInterface class description.
 *
 * @package    Components
 * @subpackage Exporter\Contracts
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
interface FieldTypeInterface extends \Bridge\Components\Exporter\Contracts\ConstraintInterface
{

    /**
     * Get default value.
     *
     * @return mixed
     */
    public function getDefaultValue();

    /**
     * Get the field type length.
     *
     * @return mixed
     */
    public function getFieldLength();

    /**
     * Get the type name property.
     *
     * @return string
     */
    public function getTypeName();
}
