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
interface TableEntityInterface extends \Bridge\Components\Exporter\Contracts\EntityInterface
{

    /**
     * Delete the selected table entity record row.
     *
     * @param array $conditions Condition to select the specific row.
     *
     * @return void
     */
    public function doDeleteRow(array $conditions);

    /**
     * Insert new table entity record row from data collection.
     *
     * @param array $data Data that will be inserted.
     *
     * @return void
     */
    public function doInsertRow(array $data);

    /**
     * Update table entity record row.
     *
     * @param array $data       Data that will be updated.
     * @param array $conditions Condition to select the specific row.
     *
     * @return void
     */
    public function doUpdateRow(array $data, array $conditions);

    /**
     * Get the constraint entity object as the table entity constraint data property.
     *
     * @return \Bridge\Components\Exporter\Contracts\ConstraintEntityInterface
     */
    public function getConstraintEntityObject();

    /**
     * Check if the table entity has a constraint.
     *
     * @return boolean
     */
    public function hasConstraint();
}
