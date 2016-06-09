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
 * ExcelFile class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class BasicExcelFile extends \Bridge\Components\Exporter\AbstractExcelFile
{

    /**
     * Add one row to the excel file.
     *
     * @param array  $row       Complete array for one row to add.
     * @param string $sheetName Sheet name parameter.
     * @param string $gridType  Grid type parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If any error raised when adding row into the grid.
     *
     * @return void
     */
    public function addRow(array $row, $sheetName = '', $gridType = 'contents')
    {
        try {
            $nextGridRow = $this->getNextGridRow($sheetName, $gridType);
            $this->Grid['worksheets'][$sheetName][$gridType][$nextGridRow] = $this->checkGridRows($row);
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Printing the excel document.
     *
     * @param array  $options   Option array set to printing mode parameter.
     * @param string $sheetName Sheet name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If failed to get page setup or margin instance.
     *
     * @return void
     */
    public function doPrinting(array $options = [], $sheetName = '')
    {
        # Configuration that supported only for page setup and margin.
        if (array_key_exists('setup', $options) === true) {
            $pageSetupData = $options['setup'];
            $mapperFunction = [
                'orientation' => 'setOrientation',
                'paperSize'   => 'setPaperSize',
                'fitToWidth'  => 'setFitToWidth',
                'fitToHeight' => 'setFitToHeight',
                'scale'       => 'setScale',
                'printArea'   => 'setPrintArea'
            ];
            $pageSetupObject = $this->getPageSetup($sheetName);
            $this->setupPrinting($pageSetupObject, $mapperFunction, $pageSetupData);
        }
        if (array_key_exists('margin', $options) === true) {
            $pageMarginData = $options['margin'];
            $mapperFunction = [
                'top'    => 'setTop',
                'right'  => 'setRight',
                'left'   => 'setLeft',
                'bottom' => 'setBottom'
            ];
            $pageMarginObject = $this->getPageMargin($sheetName);
            $this->setupPrinting($pageMarginObject, $mapperFunction, $pageMarginData);
        }
        # Applied the options to the excel object.
    }

    /**
     * Returns the number of lines in the grid.
     *
     * @param string $sheetName Sheet name parameter.
     * @param string $gridType  Grid type parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If cannot get next grid row number.
     *
     * @return integer Returns the next grid row number
     */
    public function getNextGridRow($sheetName = '', $gridType = 'contents')
    {
        if (array_key_exists($sheetName, $this->getGrid()['worksheets']) === false or
            $this->validateGridType($gridType) === false
        ) {
            throw new \Bridge\Components\Exporter\ExporterException('Cannot get next grid row number');
        }
        return max(array_keys($this->Grid['worksheets'][$sheetName][$gridType])) + 1;
    }

    /**
     * Set the complete grid.
     *
     * @param array $grid The complete grid.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If worksheet not found at given grid data.
     *
     * @return void
     */
    public function setGrid(array $grid)
    {
        if (array_key_exists('worksheets', $grid) === false) {
            throw new \Bridge\Components\Exporter\ExporterException('Worksheet not found at given grid data');
        }
        # Start to looping under the sheet and convert the cell values if br tag found.
        foreach ($grid['worksheets'] as $sheetKey => $sheetData) {
            foreach ($sheetData as $groupKey => $groupData) {
                foreach ($groupData as $rowKey => $rowData) {
                    $rowData = $this->checkGridRows($rowData);
                    $this->Grid['worksheets'][$sheetKey][$groupKey][$rowKey] = $rowData;
                }
            }
        }
        $this->Grid = $grid;
    }

    /**
     * Get the complete grid as a string.
     *
     * @throws \Bridge\Components\Exporter\ExporterException Failed to render the grid.
     *
     * @return void
     */
    protected function doRenderGrid()
    {
        try {
            if (count($this->getGrid()) > 0) {
                # Remove the default worksheet.
                $this->getPhpExcelObject()->removeSheetByIndex($this->getSheetIndex());
                $gridData = $this->getGrid();
                $gridWorksheet = (array)$gridData['worksheets'];
                $objPhpExcel = $this->getPhpExcelObject();
                $worksheetIndex = 0;
                foreach ($gridWorksheet as $sheetKey => $sheetData) {
                    # Create the worksheet instance.
                    $sheetKeyAsString = $sheetKey;
                    if (is_numeric($sheetKeyAsString) === true) {
                        $sheetKeyAsString = 'sheet' . $sheetKey;
                    }
                    $objWorksheet = new \PHPExcel_Worksheet($objPhpExcel, $sheetKeyAsString);
                    # Or you can use $objWorksheet = $this->getPhpExcelObject()->createSheet();
                    $objWorksheet->setTitle($sheetKeyAsString);
                    # Attach the worksheet to php excel object.
                    $objPhpExcel->addSheet($objWorksheet, $worksheetIndex);
                    $objPhpExcel->setActiveSheetIndex($worksheetIndex);
                    # Parse all the sheet data to variable.
                    # Process and render the sheet data.
                    if (array_key_exists('contents', $sheetData) === true) {
                        $this->doRenderGridItems($sheetData['contents']);
                    }
                    # worksheet index increment to set the active sheet.
                    $worksheetIndex++;
                }
                # Remove all the grid content after rendering the grid.
                $this->doUnsetGrid();
            }
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException('Failed to render the grid: ' . $ex->getMessage());
        }
    }

    /**
     * Render the grid item.
     *
     * @param array $gridItems Grid item array data parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid grid item given.
     *
     * @return void
     */
    protected function doRenderGridItems(array $gridItems)
    {
        try {
            # Loop under the grid items.
            foreach ($gridItems as $rowNumber => $gridRow) {
                $gridRowStyles = [];
                # Check if there is a grid row cell range styles data and then validate that styles array.
                if (array_key_exists('styles', $gridRow) === true and
                    $this->validateGridStyles($gridRow['styles']) === true
                ) {
                    $gridRowStyles = $gridRow['styles'];
                }
                # Check if the data key exists on every grid row.
                if (array_key_exists('data', $gridRow) === true) {
                    foreach ($gridRow['data'] as $columnNumber => $value) {
                        $selectedColumnNumber = $this->getStringFromColumnIndex($columnNumber);
                        $this->getSheet()->getColumnDimension($selectedColumnNumber)->setAutoSize(true);
                        $cellData = $value;
                        # Convert the cell data into standard array so it will be more easy to process the rendering.
                        if (is_array($cellData) === false) {
                            $cellData = ['value' => $value];
                        }
                        # Apply the grid cell range style for each cell.
                        if (count($gridRowStyles) > 0) {
                            $this->getStyle($this->getCoordinate($columnNumber, $rowNumber))->applyFromArray(
                                $gridRowStyles
                            );
                        }
                        # Apply the individual cell styles data.
                        if (array_key_exists('styles', $cellData) === true and
                            $this->validateGridStyles($cellData['styles']) === true
                        ) {
                            $this->getStyle($this->getCoordinate($columnNumber, $rowNumber))->applyFromArray(
                                $cellData['styles']
                            );
                        }
                        # Set the value into selected cell.
                        if (array_key_exists('value', $cellData) === true) {
                            $this->setCell($cellData['value'], $columnNumber, $rowNumber);
                        }
                    }
                }
            }
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Process to convert break line to new line for passed cell.
     *
     * @param array $rowData Rows data parameter.
     *
     * @return array
     */
    private function checkGridRows(array $rowData)
    {
        # Check first if data key exists on passed row data array.
        if (array_key_exists('data', $rowData) === true) {
            $rowArr = $rowData['data'];
            foreach ($rowArr as $column => $cell) {
                # Check if the cell variable is array or not, and then convert the cell value.
                if (is_array($cell) === true and array_key_exists('value', $cell) === true) {
                    $cell['value'] = $this->doConvertBrToNl($cell['value']);
                } else {
                    $cell = $this->doConvertBrToNl($cell);
                }
                $rowData['data'][$column] = $cell;
            }
        }
        # Return the row data.
        return $rowData;
    }

    /**
     * Setup printing option to excel file.
     *
     * @param Contracts\ExcelPageElementInterface $pageElementObj Page setup or margin instance.
     * @param array                               $mapperFunction Mapper function array data that will be called
     *                                                            runtime dynamically.
     * @param array                               $optionsData    Printing options data parameter.
     *
     * @return void
     */
    private function setupPrinting($pageElementObj, array $mapperFunction, array $optionsData)
    {
        $callableMethod = array_keys($mapperFunction);
        foreach ($optionsData as $key => $value) {
            if (array_key_exists($key, $callableMethod) === true and
                method_exists($pageElementObj, $mapperFunction[$key])
            ) {
                $pageElementObj->{$mapperFunction[$key]}($value);
            }
        }
    }

    /**
     * Validate the given grid type.
     *
     * @param string $gridType Grid type parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid grid type given.
     *
     * @return boolean
     */
    private function validateGridType($gridType)
    {
        $validGridType = ['header', 'contents', 'footer'];
        if (in_array($gridType, $validGridType, true) === false) {
            throw new \Bridge\Components\Exporter\ExporterException('Invalid grid type');
        }
        return true;
    }
}
