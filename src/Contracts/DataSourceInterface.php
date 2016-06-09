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
 * ExporterDataSourceInterface class description.
 *
 * @package    Components
 * @subpackage Exporter\Contracts
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
interface DataSourceInterface
{

    /**
     * Load the data source and run initial process.
     *
     * @return void
     */
    public function doLoad();

    /**
     * Do mass import data set.
     *
     * @param array $data Data that will be updated into data source.
     *
     * @return void
     */
    public function doMassImport(array $data);

    /**
     * Get resource data.
     *
     * @param array $entityFilter Selected entity collection that will be parsed.
     *
     * @return array
     */
    public function getData(array $entityFilter = []);

    /**
     * Get field lists from data source.
     *
     * @param string $entityName Selected entity name that will be fetch the field column list.
     *
     * @return array
     */
    public function getFields($entityName = '');
}
