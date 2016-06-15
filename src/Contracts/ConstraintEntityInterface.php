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
 * ConstraintEntityInterface class description.
 *
 * @package    Components
 * @subpackage Exporter\Contracts
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
interface ConstraintEntityInterface extends \Bridge\Components\Exporter\Contracts\EntityInterface
{

    /**
     * Get the table entity object that owned the constraint instance.
     *
     * @return \Bridge\Components\Exporter\Contracts\TableEntityInterface
     */
    public function getTableEntityObject();

    /**
     * Validate the table entity data.
     *
     * @param \Bridge\Components\Exporter\Contracts\TableEntityInterface $tableEntityObj Table entity object parameter.
     *
     * @return boolean
     */
    public function validateTableEntityData(\Bridge\Components\Exporter\Contracts\TableEntityInterface $tableEntityObj);
}
