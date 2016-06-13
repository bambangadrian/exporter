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
 * ExporterObserverInterface class description.
 *
 * @package    Components
 * @subpackage Exporter\Contracts
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
interface ExporterObserverInterface
{

    /**
     * Receive update from subject.
     *
     * @param \Bridge\Components\Exporter\Contracts\ExporterSubjectInterface $exporterSubject The Subject that
     *                                                                                        notifying the observer of
     *                                                                                        an update.
     *
     * @return void
     */
    public function update(\Bridge\Components\Exporter\Contracts\ExporterSubjectInterface $exporterSubject);
}
