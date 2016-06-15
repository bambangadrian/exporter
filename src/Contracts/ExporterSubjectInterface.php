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
 * ExporterSubjectInterface class description.
 *
 * @package    Components
 * @subpackage Exporter\Contracts
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
interface ExporterSubjectInterface
{

    /**
     * Attach an observer.
     *
     * @param \Bridge\Components\Exporter\Contracts\ExporterObserverInterface $exporterObserver The observer parameter.
     *
     * @return void
     */
    public function attachObserver(\Bridge\Components\Exporter\Contracts\ExporterObserverInterface $exporterObserver);

    /**
     * Detach an observer.
     *
     * @param \Bridge\Components\Exporter\Contracts\ExporterObserverInterface $exporterObserver The observer parameter.
     *
     * @return void
     */
    public function detachObserver(\Bridge\Components\Exporter\Contracts\ExporterObserverInterface $exporterObserver);

    /**
     * Notify all the observers that has been registered.
     *
     * @return void
     */
    public function doNotifyToAllObserver();

    /**
     * Get all observers that has been assigned to the exporter subject instance.
     *
     * @return \Bridge\Components\Exporter\Contracts\ExporterObserverInterface[]
     */
    public function getAllObservers();

    /**
     * Get message state property.
     *
     * @return string
     */
    public function getEventMessage();

    /**
     * Get event name property.
     *
     * @return string
     */
    public function getEventName();

    /**
     * Get event state property.
     *
     * @return integer
     */
    public function getEventState();

    /**
     * Get exporter subject name property.
     *
     * @return string
     */
    public function getSubjectName();
}
