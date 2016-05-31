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
 * TableEntityInterface class description.
 *
 * @package    Components
 * @subpackage Exporter\Contracts
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
interface TableEntityInterface
{

    /**
     * Get table entity data property.
     *
     * @return array
     */
    public function getData();

    /**
     * Get selected field property.
     *
     * @param string $fieldName Field name parameter.
     *
     * @return \Bridge\Components\Exporter\Contracts\FieldElementInterface
     */
    public function getField($fieldName);

    /**
     * Get fields collection information.
     *
     * @return array Field element object collection.
     */
    public function getFields();

    /**
     * Get table name property.
     *
     * @return string Get table entity name.
     */
    public function getName();
}
