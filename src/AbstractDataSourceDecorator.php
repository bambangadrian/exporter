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
 * AbstractDataSourceDecorator class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
abstract class AbstractDataSourceDecorator implements \Bridge\Components\Exporter\Contracts\DataSourceInterface
{

    /**
     * Data source real object.
     *
     * @var \Bridge\Components\Exporter\Contracts\DataSourceInterface $DataSourceInstance
     */
    protected $DataSourceInstance;

    /**
     * AbstractDataSourceDecorator constructor.
     *
     * @param \Bridge\Components\Exporter\Contracts\DataSourceInterface $dataSource Data source instance parameter.
     */
    public function __construct(\Bridge\Components\Exporter\Contracts\DataSourceInterface $dataSource)
    {
        $this->DataSourceInstance = $dataSource;
    }

    /**
     * Load the data source and run initial process.
     *
     * @return void
     */
    public function doLoad()
    {
        $this->DataSourceInstance->doLoad();
    }

    /**
     * Do mass import data set.
     *
     * @param array $data Data that will be updated into data source.
     *
     * @return void
     */
    public function doMassImport(array $data)
    {
        $this->DataSourceInstance->doMassImport($data);
    }

    /**
     * Get resource data.
     *
     * @param array $entityFilter Selected entity collection that will be parsed.
     *
     * @return array
     */
    public function getData(array $entityFilter = [])
    {
        return $this->DataSourceInstance->getData($entityFilter);
    }

    /**
     * Get field lists from data source.
     *
     * @param string $entityName Selected entity name that will be fetch the field column list.
     *
     * @return array
     */
    public function getFields($entityName = '')
    {
        return $this->DataSourceInstance->getFields($entityName);
    }
}
