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
 * ArrayDataSource class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class ArrayDataSource extends \Bridge\Components\Exporter\AbstractDataSource
{

    /**
     * ArrayDataSource constructor.
     *
     * @param array $arrayData Data parameter.
     */
    public function __construct(array $arrayData)
    {
        $this->setData($arrayData);
    }

    /**
     * Load the data source and run initial process.
     *
     * @return void
     */
    public function doLoad()
    {
        $loadedCollection = array_map(
            function ($val) {
                return strtolower($val);
            },
            $this->getLoadedEntities()
        );
        $collections = $this->getData();
        # Get the all the fields and build the correct data structure.
        foreach ($collections as $collectionName => $collectionData) {
            if (count($loadedCollection) > 0 and
                in_array(strtolower($collectionName), $loadedCollection, true) === false
            ) {
                continue;
            }
            $fields = [];
            foreach ((array)$collectionData as $rows) {
                $collectionFields = array_keys($rows);
                if (count($fields) === 0) {
                    $fields = $collectionFields;
                } elseif (count($newFields = array_diff($collectionFields, $fields)) > 0) {
                    foreach ($newFields as $newField) {
                        $fields[] = $newField;
                    }
                }
            }
            $this->Fields[$collectionName] = $fields;
            # Looping again the collection data after we get the fields for the selected collection.
            $this->Data[$collectionName] = $this->getBuildDataRecord($collectionName, $collectionData);
        }
        if (count($this->getData()) > 1) {
            $this->setMultipleSource(true);
        }
    }

    /**
     * Get data source handler instance.
     *
     * @return \Bridge\Components\Exporter\Contracts\DataSourceHandlerInterface
     */
    public function getDataSourceHandler()
    {
        return new \Bridge\Components\Exporter\ArrayHandler($this->getData());
    }

    /**
     * Get build or formatted data record from array collection.
     *
     * @param string $collectionName Collection name parameter.
     * @param array  $collectionData Collection data parameter.
     *
     * @return array
     */
    private function getBuildDataRecord($collectionName, array $collectionData)
    {
        $records = [];
        $fieldCollection = $this->getFields($collectionName);
        # Looping again the collection data after we get the fields for the selected collection.
        foreach ($collectionData as $index => $rows) {
            $notFoundFieldCollection = array_diff($fieldCollection, array_keys($rows));
            # Set null value for not found field name.
            foreach ($notFoundFieldCollection as $notFoundField) {
                $records[$index][$notFoundField] = null;
            }
            foreach ((array)$rows as $field => $value) {
                $records[$index][$field] = $value;
            }
        }
        return $records;
    }
}
