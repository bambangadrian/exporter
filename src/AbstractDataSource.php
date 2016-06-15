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
 * AbstractDataSource class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
abstract class AbstractDataSource implements \Bridge\Components\Exporter\Contracts\DataSourceInterface
{

    /**
     * Entity data collection property.
     *
     * @var array $Data
     */
    protected $Data = [];

    /**
     * Fields data collection property.
     *
     * @var array $Fields
     */
    protected $Fields = [];

    /**
     * Selected entities that will be loaded.
     *
     * @var array $LoadedEntities
     */
    protected $LoadedEntities = [];

    /**
     * Multiple source options.
     *
     * @var boolean $MultipleSource
     */
    protected $MultipleSource = false;

    /**
     * Get resource data.
     *
     * @param array $entityFilter Selected entity collection that will be parsed.
     *
     * @return array
     */
    public function getData(array $entityFilter = [])
    {
        $filteredData = $this->Data;
        if (count(array_filter($entityFilter)) > 0 and $this->isMultipleSource() === true) {
            $existingEntities = array_keys($filteredData);
            foreach ($existingEntities as $entityName) {
                if (in_array($entityName, $entityFilter, true) === false) {
                    unset($filteredData[$entityName]);
                }
            }
        }
        return $filteredData;
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
        if (array_key_exists($entityName, $this->Fields) === true) {
            return $this->Fields[$entityName];
        }
        return $this->Fields;
    }

    /**
     * Get loaded entities property
     *
     * @return array
     */
    public function getLoadedEntities()
    {
        return $this->LoadedEntities;
    }

    /**
     * Check if instance handle multiple data source.
     *
     * @return boolean
     */
    public function isMultipleSource()
    {
        return $this->MultipleSource;
    }

    /**
     * Set entities that will be loaded.
     *
     * @param array $loadedEntities Loaded entities data array parameter.
     *
     * @return void
     */
    public function setLoadedEntities(array $loadedEntities = [])
    {
        $this->LoadedEntities = $loadedEntities;
    }

    /**
     * Set record set data from excel data source.
     *
     * @param array $data Data array that contain record set parameter.
     *
     * @return void
     */
    protected function setData(array $data = [])
    {
        $this->Data = $data;
    }

    /**
     * Set fields property
     *
     * @param array $fields Fields data array parameter.
     *
     * @return void
     */
    protected function setFields($fields)
    {
        $this->Fields = $fields;
    }

    /**
     * Set the multiple source flag option into
     *
     * @param boolean $multipleSource Multi source option flag parameter.
     *
     * @return void
     */
    protected function setMultipleSource($multipleSource = true)
    {
        $this->MultipleSource = $multipleSource;
    }
}
