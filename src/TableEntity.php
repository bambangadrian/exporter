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
namespace Bridge\Components\Exporter;

/**
 * TableEntity class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class TableEntity extends \Bridge\Components\Exporter\AbstractEntity implements
    \Bridge\Components\Exporter\Contracts\TableEntityInterface
{

    /**
     * Constraint entity instance property.
     *
     * @var \Bridge\Components\Exporter\Contracts\ConstraintEntityInterface
     */
    private $ConstraintEntity;

    /**
     * TableEntity constructor.
     *
     * @param string                              $entityName          Entity name parameter.
     * @param Contracts\DataSourceInterface       $dataSourceObj       Data source instance parameter.
     * @param Contracts\ConstraintEntityInterface $constraintEntityObj Constraint entity object parameter.
     */
    public function __construct(
        $entityName,
        Contracts\DataSourceInterface $dataSourceObj,
        Contracts\ConstraintEntityInterface $constraintEntityObj = null
    ) {
        if ($constraintEntityObj !== null) {
            $this->setConstraintEntityObject($constraintEntityObj);
            $constraintEntityObj->validateTableEntityData($this);
        }
        parent::__construct($entityName, $dataSourceObj);
    }

    /**
     * Delete the selected table entity record row.
     *
     * @param array $condition Condition to select the specific row.
     *
     * @return boolean
     */
    public function deleteRow(array $condition)
    {
        # TODO: Implement deleteRow() method.
    }

    /**
     * Get the constraint entity object as the table entity constraint data property.
     *
     * @return \Bridge\Components\Exporter\Contracts\ConstraintEntityInterface
     */
    public function getConstraintEntityObject()
    {
        return $this->ConstraintEntity;
    }

    /**
     * Check if the table entity has a constraint.
     *
     * @return boolean
     */
    public function hasConstraint()
    {
        return ($this->getConstraintEntityObject() !== null);
    }

    /**
     * Insert new table entity record row from data collection.
     *
     * @param array $data Data that will be inserted.
     *
     * @return boolean
     */
    public function insertRow(array $data)
    {
        # TODO: Implement insertRow() method.
    }

    /**
     * Update table entity record row.
     *
     * @param array $data      Data that will be updated.
     * @param array $condition Condition to select the specific row.
     *
     * @return boolean
     */
    public function updateRow(array $data, array $condition)
    {
        # TODO: Implement updateRow() method.
    }

    /**
     * Set the constraint entity object property.
     *
     * @param \Bridge\Components\Exporter\Contracts\ConstraintEntityInterface|null $constraintEntityObj Constraint
     *                                                                                                  entity object
     *                                                                                                  parameter.
     *
     * @return void
     */
    protected function setConstraintEntityObject(
        \Bridge\Components\Exporter\Contracts\ConstraintEntityInterface $constraintEntityObj = null
    ) {
        $this->ConstraintEntity = $constraintEntityObj;
    }
}
