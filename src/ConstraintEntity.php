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
 * ConstraintEntity class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class ConstraintEntity extends \Bridge\Components\Exporter\AbstractEntity implements
    \Bridge\Components\Exporter\Contracts\ConstraintEntityInterface
{

    /**
     * Table entity instance property.
     *
     * @var \Bridge\Components\Exporter\Contracts\TableEntityInterface
     */
    private $TableEntity;


    /**
     * Get the table entity object that owned the constraint instance.
     *
     * @return \Bridge\Components\Exporter\Contracts\TableEntityInterface
     */
    public function getTableEntityObject()
    {
        return $this->TableEntity;
    }



    /**
     * Validate the table entity data.
     *
     * @param \Bridge\Components\Exporter\Contracts\TableEntityInterface $tableEntityObj Table entity object parameter.
     *
     * @return boolean
     */
    public function validateTableEntityData(\Bridge\Components\Exporter\Contracts\TableEntityInterface $tableEntityObj)
    {
        $this->setTableEntityObject($tableEntityObj);
        return true;
    }

    /**
     * Set the table entity object property.
     *
     * @param \Bridge\Components\Exporter\Contracts\TableEntityInterface $tableEntityObj Table entity object parameter.
     *
     * @return void
     */
    protected function setTableEntityObject(\Bridge\Components\Exporter\Contracts\TableEntityInterface $tableEntityObj)
    {
        $this->TableEntity = $tableEntityObj;
    }
}
