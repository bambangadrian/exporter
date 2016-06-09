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
 * DataMapper class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class DataMapper implements \Bridge\Components\Exporter\Contracts\MapperInterface
{

    /**
     * Field mapper data property.
     *
     * @var array $FieldMapperData
     */
    private $FieldMapperData;

    /**
     * Result of data mapper processing.
     *
     * @var array $Result
     */
    private $Result;

    /**
     * Source entity object that will be used as the valid model.
     *
     * @var \Bridge\Components\Exporter\Contracts\TableEntityInterface $SourceEntity
     */
    private $SourceEntity;

    /**
     * Target entity object that will be mapped to the source entity.
     *
     * @var \Bridge\Components\Exporter\Contracts\TableEntityInterface $TargetEntity
     */
    private $TargetEntity;

    /**
     * DataMatcher constructor.
     *
     * @param \Bridge\Components\Exporter\Contracts\TableEntityInterface $sourceEntityObj Source entity object param.
     */
    public function __construct(\Bridge\Components\Exporter\Contracts\TableEntityInterface $sourceEntityObj = null)
    {
        $this->SourceEntity = $sourceEntityObj;
    }

    /**
     * Run mapper procedure.
     *
     * @param boolean $replaceSourceData Replace all source data flag option parameter.
     * @param boolean $reIndex           Re-index all the mapper data result keys flag option parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If any error raised when run the mapper procedure.
     *
     * @return void
     */
    public function doMapping($replaceSourceData = true, $reIndex = true)
    {
        $result = [];
        # Get the source entity data.
        $sourceData = $this->getSourceEntityObject()->getData();
        # Get the target entity data.
        $targetData = $this->getTargetEntityObject()->getData();
        if ($reIndex === true) {
            $sourceData = array_values($sourceData);
            $targetData = array_values($targetData);
        }
        # Compare the field/header between the source and target entity.
        # Step: Get the field header for each entity that has been mapped.
        if ($this->validateFieldMapperData() === true) {
            # Map all the target data using the field data mapper and render into data collection.
            $fieldDataMapper = $this->getFieldMapperData();
            foreach ($targetData as $rowNumber => $rows) {
                foreach ($fieldDataMapper as $sourceField => $targetField) {
                    $result[$rowNumber][$sourceField] = $rows[$targetField];
                }
            }
            if ($replaceSourceData === false) {
                $result = array_merge($sourceData, $result);
            }
            # If the mapping data is success and entity constraint has been defined then do:
            # Verify/check all the data type constraint for each field.
            $this->doValidateDataConstraint($result);
            # Set the Result property content = the mapper result data.
            $this->Result = $result;
        }
    }

    /**
     * Get the mapper data property.
     *
     * @return array
     */
    public function getFieldMapperData()
    {
        return $this->FieldMapperData;
    }

    /**
     * Get mapped data result.
     *
     * @param array $fieldFilters Field filters data that will be retrieved from the mapped data result.
     *
     * @return array
     */
    public function getMappedData(array $fieldFilters = [])
    {
        $mappedData = $this->Result;
        if (count($fieldFilters) > 0) {
            foreach ($mappedData as $rowNumber => $rowData) {
                # Get all field keys for each row.
                $fieldNames = array_keys($rowData);
                foreach ($fieldNames as $fieldName) {
                    # Filter the field.
                    if (in_array($fieldName, $fieldFilters, true) === false) {
                        unset($mappedData[$rowNumber][$fieldName]);
                    }
                }
            }
        }
        return $mappedData;
    }

    /**
     * Get the source entity object instance property.
     *
     * @return \Bridge\Components\Exporter\Contracts\TableEntityInterface
     */
    public function getSourceEntityObject()
    {
        return $this->SourceEntity;
    }

    /**
     * Get the target entity object instance property.
     *
     * @return \Bridge\Components\Exporter\Contracts\TableEntityInterface
     */
    public function getTargetEntityObject()
    {
        return $this->TargetEntity;
    }

    /**
     * Set the array data mapper property.
     *
     * @param array $dataMapper Data mapper property.
     *
     * @return void
     */
    public function setFieldMapperData(array $dataMapper = [])
    {
        $this->FieldMapperData = $dataMapper;
    }

    /**
     * Set table entity property that will act as the virtual document rules.
     *
     * @param \Bridge\Components\Exporter\Contracts\TableEntityInterface $sourceEntityObj Source entity object param.
     *
     * @return void
     */
    public function setSourceEntity(\Bridge\Components\Exporter\Contracts\TableEntityInterface $sourceEntityObj)
    {
        $this->SourceEntity = $sourceEntityObj;
    }

    /**
     * Set the data target that will be compared and matched with the data source.
     *
     * @param \Bridge\Components\Exporter\Contracts\TableEntityInterface $targetEntityObj Target entity object param.
     *
     * @return void
     */
    public function setTargetEntity(\Bridge\Components\Exporter\Contracts\TableEntityInterface $targetEntityObj)
    {
        $this->TargetEntity = $targetEntityObj;
    }

    /**
     * Validate the data mapper result by the assigned source constraint entity object.
     *
     * @param array $dataMapperResult Data mapper result parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid constraint check on mapper result data detected.
     *
     * @return void
     */
    private function doValidateDataConstraint(array $dataMapperResult)
    {
        if ($this->getSourceEntityObject()->getConstraintEntityObject() !== null) {
            foreach ($dataMapperResult as $rows) {
                foreach ((array)$rows as $field => $value) {
                    $fieldObj = $this->getSourceEntityObject()->getField($field);
                    if ($fieldObj->getFieldTypeObject()->validateConstraint($value) === false) {
                        throw new \Bridge\Components\Exporter\ExporterException(
                            'Invalid constraint check on mapper result data detected: ' . $value
                        );
                    }
                }
            }
        }
    }

    /**
     * Validate the field mapper data property.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If some field mapper not found on source entity.
     * @throws \Bridge\Components\Exporter\ExporterException If some field mapper not found on target entity.
     *
     * @return boolean
     */
    private function validateFieldMapperData()
    {
        # Get field mapper data.
        $fieldMapperData = $this->getFieldMapperData();
        # Check if all the keys on field mapper data are exist on the source entity.
        if (count(array_diff_key($fieldMapperData, $this->getSourceEntityObject()->getFields())) !== 0) {
            throw new \Bridge\Components\Exporter\ExporterException('Some field mapper not found on source entity');
        }
        # Check if all the keys on field mapper data are exist on the target entity.
        if (count(array_diff($fieldMapperData, array_keys($this->getTargetEntityObject()->getFields()))) !== 0) {
            throw new \Bridge\Components\Exporter\ExporterException('Some field mapper not found on target entity');
        }
        # Return true if field mapper data is valid.
        return true;
    }
}
