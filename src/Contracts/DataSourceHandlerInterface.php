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
 * DataSourceHandlerInterface class description.
 *
 * @package    Components
 * @subpackage Exporter\Contracts
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
interface DataSourceHandlerInterface
{

    /**
     * Do mass import to data source.
     *
     * @param array $data Data collection that will be imported.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid exported data structure found.
     *
     * @return array
     */
    public function getFormattedImportData(array $data);
}
