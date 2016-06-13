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
 * ExporterInterface class description.
 *
 * @package    Components
 * @subpackage Exporter\Contracts
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
interface ExporterInterface extends \Bridge\Components\Exporter\Contracts\ExporterObserverInterface
{

    /**
     * Error state on exporting process.
     *
     * @constant STATE_ERROR
     */
    const STATE_ERROR = 1;

    /**
     * Failed state on exporting process.
     *
     * @constant STATE_FAILED
     */
    const STATE_FAILED = 2;

    /**
     * Success state on exporting process.
     *
     * @constant STATE_SUCCESS
     */
    const STATE_SUCCESS = 0;

    /**
     * Do the data export.
     *
     * @return void
     */
    public function doExport();

    /**
     * Get exported entity instance.
     *
     * @return \Bridge\Components\Exporter\Contracts\TableEntityInterface
     */
    public function getExportedEntityObject();

    /**
     * Get log data property.
     *
     * @return array
     */
    public function getLog();

    /**
     * Get exporter status.
     *
     * @return self::STATE_ERROR|self::STATE_FAILED|self::STATE_SUCCESS
     */
    public function getStatus();

    /**
     * Get exporter target entity instance property.
     *
     * @return \Bridge\Components\Exporter\Contracts\TableEntityInterface
     */
    public function getTargetEntityObject();
}
