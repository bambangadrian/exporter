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
 * ExcelDataSource class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class ExcelDataSource extends \Bridge\Components\Exporter\AbstractDataSource
{

    /**
     * Excel file object property.
     *
     * @var \Bridge\Components\Exporter\AbstractExcelFile $ExcelFile
     */
    private $ExcelFile;

    /**
     * Field read filter that will be used to filter the field row.
     *
     * @var \Bridge\Components\Exporter\ExcelEntityFieldsReadFilter $FieldReadFilter
     */
    private $FieldReadFilter;

    /**
     * Record read filter that will be used to filter the contents row.
     *
     * @var \Bridge\Components\Exporter\ExcelEntityRecordReadFilter $RecordReadFilter
     */
    private $RecordReadFilter;

    /**
     * ExcelDataSource constructor.
     *
     * @param string $filePath Excel file path parameter.
     * @param string $type     The reader type that indicates the excel file type.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid excel data source file path given.
     * @throws \Bridge\Components\Exporter\ExporterException If any error raised when construct the instance.
     */
    public function __construct($filePath, $type = 'Excel2007')
    {
        try {
            if (trim($filePath) === '' or $filePath === null or file_exists($filePath) === false) {
                throw new \Bridge\Components\Exporter\ExporterException('Invalid excel data source file path');
            }
            $this->ExcelFile = new \Bridge\Components\Exporter\BasicExcelFile($filePath);
            $this->ExcelFile->setReaderAndWriterType($type);
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Load the excel file and run initial process.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If failed to read the excel source data.
     *
     * @return void
     */
    public function doLoad()
    {
        # Set the default field read filter if no given.
        if ($this->getFieldReadFilter() === null) {
            $this->setFieldReadFilter(1);
        }
        $this->getExcelFileObject()->setLoadedSheets($this->getLoadedEntities());
        $this->getExcelFileObject()->doRead();
        $excelFileDataArr = $this->getExcelFileObject()->getData();
        if (array_key_exists('worksheets', $excelFileDataArr) === true) {
            $worksheetData = $excelFileDataArr['worksheets'];
            if (count($worksheetData) > 1) {
                $this->setMultipleSource(true);
            }
        }
        # Get the fields data.
        # Build the recordSet data by grouping the fields column as the index key.
        $fields = [];
        $data = [];
        foreach ($excelFileDataArr as $worksheets) {
            foreach ((array)$worksheets as $sheetName => $sheet) {
                foreach ($sheet['contents'] as $rowNumber => $rowGroup) {
                    foreach ($rowGroup['data'] as $columnNumber => $cell) {
                        if (array_key_exists($sheetName, $fields) === true and
                            array_key_exists($columnNumber, $fields[$sheetName]) === true
                        ) {
                            $columnName = $fields[$sheetName][$columnNumber];
                            $data[$sheetName][$rowNumber][$columnName] = $cell;
                        }
                        if ($rowNumber === $this->FieldReadFilter->getStartRow() and $cell !== null) {
                            $fields[$sheetName][$columnNumber] = $cell;
                        }
                    }
                }
            }
        }
        $this->setFields($fields);
        $this->setData($data);
    }

    /**
     * Set field read filter object property.
     *
     * @param integer $startRow  Field row number parameter.
     * @param array   $columns   Column range data parameter.
     * @param string  $sheetName Sheet name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If multiple number of row that given.
     *
     * @return void
     */
    public function setFieldReadFilter($startRow, array $columns = [], $sheetName = '')
    {
        $this->FieldReadFilter = new \Bridge\Components\Exporter\ExcelEntityFieldsReadFilter(
            $startRow,
            $columns,
            $sheetName
        );
        $this->RecordReadFilter = new \Bridge\Components\Exporter\ExcelEntityRecordReadFilter($this->FieldReadFilter);
    }

    /**
     * Get excel file object property.
     *
     * @return \Bridge\Components\Exporter\BasicExcelFile
     */
    protected function getExcelFileObject()
    {
        return $this->ExcelFile;
    }

    /**
     * Get field read filter object.
     *
     * @return \Bridge\Components\Exporter\ExcelEntityFieldsReadFilter
     */
    protected function getFieldReadFilter()
    {
        return $this->FieldReadFilter;
    }

    /**
     * Get record read filter instance property.
     *
     * @return \Bridge\Components\Exporter\ExcelEntityRecordReadFilter
     */
    protected function getRecordReadFilter()
    {
        return $this->RecordReadFilter;
    }
}
