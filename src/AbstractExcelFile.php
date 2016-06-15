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
 * AbstractExcelFile class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
abstract class AbstractExcelFile implements
    \Bridge\Components\Exporter\Contracts\ExcelReaderInterface,
    \Bridge\Components\Exporter\Contracts\ExcelWriterInterface,
    \Bridge\Components\Exporter\Contracts\DataSourceHandlerInterface
{

    /**
     * The content data property that fetched from excel document.
     *
     * @var array $Data
     */
    protected $Data;

    /**
     * File name property.
     *
     * @var string $FileName
     */
    protected $FileName;

    /**
     * File path property.
     *
     * @var string $FilePath
     */
    protected $FilePath;

    /**
     * Grid data array that will be saved to excel document.
     *
     * @var array $Grid
     */
    protected $Grid;

    /**
     * Loaded sheet collection property.
     *
     * @var array $LoadedSheets
     */
    protected $LoadedSheets = [];

    /**
     * Action mode property.
     *
     * @var string $Mode
     */
    protected $Mode;

    /**
     * Php excel object property.
     *
     * @var \PHPExcel $PhpExcel
     */
    protected $PhpExcel;

    /**
     * Read filter object property.
     *
     * @var \Bridge\Components\Exporter\Contracts\ExcelReadFilterInterface $ReadFilter
     */
    protected $ReadFilter;

    /**
     * Php excel reader object property.
     *
     * @var \Bridge\Components\Exporter\Contracts\ExcelReaderInterface $Reader
     */
    protected $Reader;

    /**
     * Excel reader and writer type property.
     *
     * @var string $ReaderAndWriterType
     */
    protected $ReaderAndWriterType;

    /**
     * Php excel writer object property.
     *
     * @var \PHPExcel_Writer_IWriter $Writer
     */
    protected $Writer;

    /**
     * Excel writer data options property.
     *
     * @var array $WriterOptions
     */
    protected $WriterOptions = [];

    /**
     * Data collection that contains all the key name of valid styles constraint.
     *
     * @var array $ValidStyle
     */
    protected static $ValidStyle = [
        'font'      => ['name', 'bold', 'italic', 'underline', 'strike', 'color', 'size', 'superScript', 'subScript'],
        'borders'   => [
            'allborders',
            'left',
            'right',
            'top',
            'bottom',
            'diagonal',
            'vertical',
            'horizontal',
            'diagonaldirection',
            'outline'
        ],
        'border'    => ['style', 'color'],
        'alignment' => ['horizontal', 'vertical', 'rotation', 'wrap', 'shrinkToFit', 'indent'],
        'fill'      => ['type', 'rotation', 'startcolor', 'endcolor', 'color']
    ];

    /**
     * Data collection that contains all the key name of valid page setup constraint.
     *
     * @var array
     */
    protected static $ValidPageSetup = [];

    /**
     * ExcelFile constructor.
     *
     * @param string $filePath Excel file path parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If error raised when set file path property.
     */
    public function __construct($filePath)
    {
        $this->PhpExcel = new \PHPExcel();
        $this->setFilePath($filePath);
    }

    /**
     * Convert the excel/csv file to another document version type.
     *
     * @param string $filePath    The original file path that want to be converted.
     * @param string $destination The destination file save.
     * @param string $convertTo   The type of excel writer as the destination type after conversion.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If failed to convert.
     *
     * @return void
     */
    public function doConvertTo($filePath, $destination, $convertTo)
    {
        try {
            $this->PhpExcel = \PHPExcel_IOFactory::load($filePath);
            $this->doSave($destination, $convertTo);
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException('Failed to convert: ' . $ex->getMessage());
        }
    }

    /**
     * Download the excel document.
     *
     * @param string $fileName   File name that will be set into downloaded file.
     * @param string $writerType Excel writer type that will be applied into php excel writer instance.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If content type of given writer set is not found.
     * @throw  \Bridge\Components\Exporter\ExporterException If failed to save into php output.
     *
     * @return void
     */
    public function doDownload($fileName = '', $writerType = 'Excel2007')
    {
        $contentTypeArr = [
            'Excel2007' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Excel5'    => 'application/vnd.ms-excel'
        ];
        # Get the content type string.
        if (array_key_exists($writerType, $contentTypeArr) === false) {
            throw new \Bridge\Components\Exporter\ExporterException('Content type of given writer set is not found');
        } else {
            $contentType = $contentTypeArr[$writerType];
        }
        # Set the default filename if not provided.
        if (trim($fileName) === '' or $fileName === null) {
            $fileName = $this->getFileName();
        }
        # Set the content type that will sent through the http
        header('Content-Type: ' . $contentType);
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        # Set no expire date.
        header('Expires: 0');
        # Set to always modified.
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        # Use HTTP/1.1
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        try {
            $this->doSave('php://output', $writerType);
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException(
                'Failed to save into php output: ' . $ex->getMessage()
            );
        }
    }

    /**
     * Load and read excel file.
     *
     * @param \Bridge\Components\Exporter\Contracts\ExcelReadFilterInterface $readFilter Excel read filter parameter.
     * @param array                                                          $sheetNames Sheet name data collection
     *                                                                                   parameter.
     * @param string                                                         $readerType Excel reader type parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If failed to set mode.
     * If reader and writer type that given is not supported.
     * If If failed to load the reader object.
     * If invalid column string name.
     * If no cells exist within the specified range.
     *
     * @return void
     */
    public function doRead(
        \Bridge\Components\Exporter\Contracts\ExcelReadFilterInterface $readFilter = null,
        array $sheetNames = [],
        $readerType = 'Excel2007'
    ) {
        $this->setMode('read');
        if (count($sheetNames) > 0 and $sheetNames !== null) {
            $this->setLoadedSheets($sheetNames);
        }
        $this->setReaderAndWriterType($readerType);
        if ($readFilter !== null) {
            $this->setReadFilter($readFilter);
        }
        try {
            $this->Reader = $this->createReader();
            $this->PhpExcel = $this->Reader->load($this->getFilePath());
        } catch (\PHPExcel_Reader_Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException(
                'Failed to load the reader object: ' . $ex->getMessage()
            );
        }
        $workSheetIterator = $this->getPhpExcelObject()->getWorksheetIterator();
        $loadedSheet = array_map(
            function ($val) {
                return strtolower($val);
            },
            $this->getLoadedSheet()
        );
        $gridData = [];
        foreach ($workSheetIterator as $worksheet) {
            $worksheetTitle = $worksheet->getTitle();
            if (count($loadedSheet) > 0 and in_array(strtolower($worksheetTitle), $loadedSheet, true) === false) {
                continue;
            }
            $worksheetData = [];
            $objWorkSheet = $this->setActiveSheetIndexByName($worksheetTitle);
            $rowIterator = $objWorkSheet->getRowIterator();
            foreach ($rowIterator as $row) {
                $cellIterator = $row->getCellIterator();
                $rowIndex = $row->getRowIndex();
                try {
                    $cellIterator->setIterateOnlyExistingCells(false);
                } catch (\PHPExcel_Exception $ex) {
                    throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
                }
                $rowData = [];
                foreach ($cellIterator as $cell) {
                    /**
                     * Convert cell into php excel cell object.
                     *
                     * @var \PhpExcel_Cell $objCell
                     */
                    $objCell = $cell;
                    $columnIndex = $this->getColumnIndexFromString($objCell->getColumn());
                    $rowData['data'][$columnIndex] = $objCell->getValue();
                }
                $worksheetData['contents'][$rowIndex] = $rowData;
            }
            $gridData['worksheets'][$worksheetTitle] = $worksheetData;
        }
        # Set the Data property from grid data.
        $this->Data = $gridData;
    }

    /**
     * Save the excel file document.
     *
     * @param string $fileName   File name parameter.
     * @param string $writerType Writer type that will be used to instance the writer.
     * @param array  $grid       Data content that will be rendered into excel document.
     * @param array  $options    Configuration options data that will be applied to excel writer.
     *
     * @throws \PHPExcel_Reader_Exception If no search type found for the writer type.
     * @throws \PHPExcel_Writer_Exception If fail to save the file to the location path.
     * @throws \Bridge\Components\Exporter\ExporterException If failed to set mode or set reader and writer type.
     *
     * @return void
     */
    public function doSave($fileName = '', $writerType = 'Excel2007', array $grid = [], array $options = [])
    {
        if (count($grid) > 0) {
            $this->setGrid($grid);
        }
        if (count($options) > 0) {
            $this->setWriterOptions($options);
        }
        if (trim($fileName) !== '' and $fileName !== null and $this->getFilePath() !== $fileName) {
            $this->setFilePath($fileName);
        }
        # Render the data to excel grid.
        $this->doRenderGrid();
        # Set the mode.
        $this->setMode('write');
        if ($this->getMode() === 'read') {
            $this->setMode('update');
        }
        # Crete the excel writer object based on the requirement.
        $this->setReaderAndWriterType($writerType);
        $this->Writer = $this->createWriter();
        # Save the excel document.
        $this->Writer->save($this->getFilePath());
    }

    /**
     * Get active sheet object.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If the requested sheet is out of bounds.
     *
     * @return \PHPExcel_Worksheet
     */
    public function getActiveSheet()
    {
        try {
            return $this->getPhpExcelObject()->getActiveSheet();
        } catch (\PHPExcel_Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Get excel file data property.
     *
     * @return array
     */
    public function getData()
    {
        return $this->Data;
    }

    /**
     * Get excel file name property.
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->FileName;
    }

    /**
     * Get excel file path property.
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->FilePath;
    }

    /**
     * Get grid data property.
     *
     * @return array
     */
    public function getGrid()
    {
        return $this->Grid;
    }

    /**
     * Get handler name property.
     *
     * @return string
     */
    public function getHandlerName()
    {
        return 'phpExcel';
    }

    /**
     * Get loaded sheets collection property.
     *
     * @return array
     */
    public function getLoadedSheet()
    {
        return $this->LoadedSheets;
    }

    /**
     * Get action mode property.
     *
     * @return string
     */
    public function getMode()
    {
        return $this->Mode;
    }

    /**
     * Get excel page margin instance.
     *
     * @param string $sheetName Sheet name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If failed to get active sheet object.
     *
     * @return \Bridge\Components\Exporter\ExcelFile\ExcelPageMargin
     */
    public function getPageMargin($sheetName = '')
    {
        try {
            return $this->getSheet($sheetName)->getPageMargins();
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException(
                'Cannot get page margin instance: ' . $ex->getMessage()
            );
        }
    }

    /**
     * Get the PphExcel document properties.
     *
     * @return \PHPExcel_DocumentProperties
     */
    public function getProperties()
    {
        return $this->getPhpExcelObject()->getProperties();
    }

    /**
     * Get read filter instance.
     *
     * @return \Bridge\Components\Exporter\Contracts\ExcelReadFilterInterface
     */
    public function getReadFilter()
    {
        return $this->ReadFilter;
    }

    /**
     * Get excel reader type property.
     *
     * @return string
     */
    public function getReaderAndWriterType()
    {
        return $this->ReaderAndWriterType;
    }

    /**
     * Get writer options data property.
     *
     * @return array
     */
    public function getWriterOptions()
    {
        return $this->WriterOptions;
    }

    /**
     * Set active sheet index by name.
     *
     * @param string $pValue Sheet title parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If workbook does not contain the sheet.
     *
     * @return \PHPExcel_Worksheet
     */
    public function setActiveSheetIndexByName($pValue = '')
    {
        try {
            return $this->getPhpExcelObject()->setActiveSheetIndexByName($pValue);
        } catch (\PHPExcel_Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Set filename to be opened or created.
     *
     * @param string $fileName The filename.
     *
     * @return void
     */
    public function setFileName($fileName)
    {
        $this->FileName = $fileName;
    }

    /**
     * Set loaded sheets property.
     *
     * @param array $sheetNames Sheet name data parameter.
     *
     * @return void
     */
    public function setLoadedSheets(array $sheetNames = [])
    {
        $this->LoadedSheets = $sheetNames;
    }

    /**
     * Set read filter object property.
     *
     * @param \Bridge\Components\Exporter\Contracts\ExcelReadFilterInterface $readFilter Excel read filter parameter.
     *
     * @return void
     */
    public function setReadFilter(\Bridge\Components\Exporter\Contracts\ExcelReadFilterInterface $readFilter)
    {
        $this->ReadFilter = $readFilter;
    }

    /**
     * Set excel reader and writer type property.
     *
     * @param string $readerAndWriterType The excel reader and writer type parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If reader and writer type that given is not supported.
     *
     * @return void
     */
    public function setReaderAndWriterType($readerAndWriterType)
    {
        $supportedReader = ['Excel2007', 'OOCalc', 'CSV', 'Excel5', 'Excel2003XML'];
        if (in_array($readerAndWriterType, $supportedReader, true) === false) {
            throw new \Bridge\Components\Exporter\ExporterException(
                'Reader and writer type that given is not supported yet'
            );
        }
        $this->ReaderAndWriterType = $readerAndWriterType;
    }

    /**
     * Set writer options data property.
     *
     * @param array $writerOptions Writer options array data parameter.
     *
     * @return void
     */
    public function setWriterOptions(array $writerOptions = [])
    {
        $this->WriterOptions = $writerOptions;
    }

    /**
     * Create excel reader object.
     *
     * @throws \PHPExcel_Reader_Exception If invalid reader type or the file cannot be loaded.
     *
     * @return \PHPExcel_Reader_IReader
     */
    protected function createReader()
    {
        /**
         * Excel reader instance.
         *
         * @var \Bridge\Components\Exporter\Contracts\ExcelReaderInterface|\PHPExcel_Reader_Abstract $objReader
         */
        $objReader = \PHPExcel_IOFactory::createReader($this->getReaderAndWriterType());
        if ($this->getReadFilter() !== null) {
            $objReader->setReadFilter($this->getReadFilter());
        }
        if ($objReader instanceof \PHPExcel_Reader_Abstract) {
            $objReader->setReadDataOnly(true);
        }
        # Only load the loaded sheet property that has been set (optimizing memory).
        if (count($this->getLoadedSheet()) === 0) {
            $objReader->setLoadAllSheets();
        } else {
            $objReader->setLoadSheetsOnly($this->getLoadedSheet());
        }
        return $objReader;
    }

    /**
     * Create excel writer object.
     *
     * @throws \PHPExcel_Reader_Exception If invalid writer type or the file cannot be loaded.
     * @throws \Bridge\Components\Exporter\ExporterException If invalid writer option parameter given.
     *
     * @return \PHPExcel_Writer_IWriter
     */
    protected function createWriter()
    {
        try {
            $objWriter = \PHPExcel_IOFactory::createWriter($this->getPhpExcelObject(), $this->getReaderAndWriterType());
            if (count($this->getWriterOptions()) > 0) {
                \Bridge\Components\Exporter\WriterOptions\ExcelWriterOptionFactory::createOption(
                    $objWriter,
                    $this->getWriterOptions()
                )->runConfigurator();
            }
            return $objWriter;
        } catch (\PHPExcel_Writer_Exception $ex) {
            throw new \PHPExcel_Reader_Exception($ex->getMessage());
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Convert BR tags to nl.
     *
     * @param string $value The string to convert.
     *
     * @return string The converted values.
     */
    protected function doConvertBrToNl($value)
    {
        return preg_replace('/\<br(\s*)?\/?\>/i', "\r\n", $value);
    }

    /**
     * Do duplicate style from source to target coordinate.
     *
     * @param string $sourceCoordinate Source coordinate parameter.
     * @param string $targetCoordinate Target coordinate parameter.
     * @param string $sourceSheetName  Source Sheet name parameter.
     * @param string $targetSheetName  Target Sheet name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If the given worksheet not found on php excel instance.
     *
     * @return \PHPExcel_Worksheet
     */
    protected function doDuplicateStyle(
        $sourceCoordinate,
        $targetCoordinate,
        $sourceSheetName = '',
        $targetSheetName = ''
    ) {
        try {
            return $this->getSheet($targetSheetName)->duplicateStyle(
                $this->getStyle($sourceCoordinate, $sourceSheetName),
                $targetCoordinate
            );
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Get the complete grid as a string.
     *
     * @return void
     */
    abstract protected function doRenderGrid();

    /**
     * Remove and unset the Grid property content.
     *
     * @return void
     */
    protected function doUnsetGrid()
    {
        unset($this->Grid);
    }

    /**
     * Get cell value from given cell coordinate.
     *
     * @param integer $column    Column parameter.
     * @param integer $row       Row parameter.
     * @param string  $sheetName Sheet name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If the given worksheet not found on php excel instance.
     *
     * @return \PhpExcel_Cell
     */
    protected function getCell($column, $row, $sheetName = '')
    {
        try {
            return $this->getSheet($sheetName)->getCell($this->getCoordinate($column, $row));
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Get cell security instance.
     *
     * @param string $coordinate Selected coordinate parameter.
     * @param string $sheetName  Sheet name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If the given worksheet not found on php excel instance.
     *
     * @return \PHPExcel_Style_Protection
     */
    protected function getCellSecurity($coordinate = 'A1', $sheetName = '')
    {
        try {
            return $this->getStyle($coordinate, $sheetName)->getProtection();
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Get column index from string.
     *
     * @param string $column The column string parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid column string name.
     *
     * @return integer
     */
    protected function getColumnIndexFromString($column = 'A')
    {
        try {
            return \PHPExcel_Cell::columnIndexFromString($column);
        } catch (\PHPExcel_Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Get comment instance of specific coordinate.
     *
     * @param string $coordinate Selected coordinate parameter.
     * @param string $sheetName  Sheet name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid coordinate given or worksheet not found on php
     *                                                       excel instance.
     *
     * @return \PHPExcel_Comment
     */
    protected function getComment($coordinate = 'A1', $sheetName = '')
    {
        try {
            return $this->getSheet($sheetName)->getComment($coordinate);
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Get the coordinate from row and column number.
     *
     * @param integer $column The selected column number.
     * @param integer $row    The selected row number.
     *
     * @return string
     */
    protected function getCoordinate($column = 0, $row = 1)
    {
        $columnLetter = $this->getStringFromColumnIndex($column);
        return $columnLetter . $row;
    }

    /**
     * Get the coordinate range string from start-end cell column and row number.
     *
     * @param integer $colStart Start column number parameter.
     * @param integer $rowStart Start row number parameter.
     * @param integer $colEnd   End column number parameter.
     * @param integer $rowEnd   End row number parameter.
     *
     * @return string
     */
    protected function getCoordinateRange($colStart = 0, $rowStart = 1, $colEnd = 0, $rowEnd = 1)
    {
        $startCoordinate = $this->getCoordinate($colStart, $rowStart);
        $endCoordinate = $startCoordinate;
        if ($rowStart !== $rowEnd and $colStart !== $colEnd and ($colEnd >= $colStart or $rowEnd >= $rowStart)) {
            $endCoordinate = $this->getCoordinate($colEnd, $rowEnd);
        }
        return $startCoordinate . (($endCoordinate !== $startCoordinate) ? ':' . $endCoordinate : '');
    }

    /**
     * Get default style instance of php excel object.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If no default style found for this workbook.
     *
     * @return \PHPExcel_Style
     */
    protected function getDefaultStyle()
    {
        try {
            return $this->getPhpExcelObject()->getDefaultStyle();
        } catch (\PHPExcel_Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Get document security instance.
     *
     * @return \PHPExcel_DocumentSecurity
     */
    protected function getDocumentSecurity()
    {
        return $this->getPhpExcelObject()->getSecurity();
    }

    /**
     * Get header and footer instance of specific or current active worksheet.
     *
     * @param string $sheetName Sheet name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If failed get the header footer instance.
     *
     * @return \PHPExcel_Worksheet_HeaderFooter
     */
    protected function getHeaderFooter($sheetName = '')
    {
        try {
            return $this->getSheet($sheetName)->getHeaderFooter();
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException(
                'Cannot get header and footer instance: ' . $ex->getMessage()
            );
        }
    }

    /**
     * Get page setup instance from specific worksheet.
     *
     * @param string $sheetName Sheet name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If failed to get page setup instance.
     *
     * @return \Bridge\Components\Exporter\ExcelFile\ExcelPageSetup
     */
    protected function getPageSetup($sheetName = '')
    {
        try {
            return $this->getSheet($sheetName)->getPageSetup();
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException(
                'Cannot get page setup instance: ' . $ex->getMessage()
            );
        }
    }

    /**
     * Get the PhpExcel object.
     *
     * @return \PHPExcel
     */
    protected function getPhpExcelObject()
    {
        return $this->PhpExcel;
    }

    /**
     * Get current active worksheet on php excel instance.
     *
     * @param string $sheetTitle Sheet title parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If the given worksheet not found on php excel instance.
     *
     * @return \PHPExcel_Worksheet
     */
    protected function getSheet($sheetTitle = '')
    {
        if (trim($sheetTitle) !== '' and $sheetTitle !== null) {
            $objWorksheet = $this->getPhpExcelObject()->getSheetByName($sheetTitle);
            if ($objWorksheet === null) {
                throw new \Bridge\Components\Exporter\ExporterException('Worksheet not found!');
            }
        } else {
            $objWorksheet = $this->getActiveSheet();
        }
        return $objWorksheet;
    }

    /**
     * Get sheet index from given sheet name.
     *
     * @param string $sheetName The sheet name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException $ex If worksheet not found on php excel instance.
     *
     * @return integer
     */
    protected function getSheetIndex($sheetName = '')
    {
        try {
            # Get the worksheet instance from sheet name.
            return $this->getPhpExcelObject()->getIndex($this->getSheet($sheetName));
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Get sheet security instance.
     *
     * @param string $sheetName Sheet name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If worksheet not found on php excel instance.
     *
     * @return \PHPExcel_Worksheet_Protection
     */
    protected function getSheetSecurity($sheetName = '')
    {
        try {
            return $this->getSheet($sheetName)->getProtection();
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Get string value of column index from number.
     *
     * @param integer $column The column number.
     *
     * @return string
     */
    protected function getStringFromColumnIndex($column = 0)
    {
        return \PHPExcel_Cell::stringFromColumnIndex($column);
    }

    /**
     * Get style of current worksheet.
     *
     * @param string $coordinate Selected coordinate parameter.
     * @param string $sheetName  Sheet name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If worksheet not found php excel instance.
     *
     * @return \PHPExcel_Style
     */
    protected function getStyle($coordinate = 'A1', $sheetName = '')
    {
        try {
            return $this->getSheet($sheetName)->getStyle($coordinate);
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Get style of current worksheet by column and row.
     *
     * @param integer $colStart  Start column number parameter.
     * @param integer $rowStart  Start row number parameter.
     * @param integer $colEnd    End column number parameter.
     * @param integer $rowEnd    End row number parameter.
     * @param string  $sheetName Sheet name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If failed to get style object.
     *
     * @return \PHPExcel_Style
     */
    protected function getStyleByColumnAndRow($colStart = 0, $rowStart = 1, $colEnd = 0, $rowEnd = 1, $sheetName = '')
    {
        return $this->getStyle(
            $this->getCoordinateRange($colStart, $rowStart, $colEnd, $rowEnd),
            $sheetName
        );
    }

    /**
     * Set break on a cell.
     *
     * @param integer $column    The column from the cell parameter.
     * @param integer $row       The row number from the cell parameter.
     * @param integer $breakType Break type (type of PHPExcel_Worksheet::BREAK_*).
     * @param string  $sheetName Sheet name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If worksheet not found on php excel instance.
     *
     * @return \PHPExcel_Worksheet
     */
    protected function setBreak($column, $row, $breakType = \PHPExcel_Worksheet::BREAK_NONE, $sheetName = '')
    {
        try {
            return $this->getSheet($sheetName)->setBreak($this->getCoordinate($column, $row), $breakType);
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Set one specific cell.
     *
     * @param string  $value     The value to place inside the cell parameter.
     * @param integer $column    The column from the cell parameter.
     * @param integer $row       The row number from the cell parameter.
     * @param string  $sheetName Sheet name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If worksheet not found on php excel instance.
     *
     * @return \PHPExcel_Cell
     */
    protected function setCell($value = null, $column = 0, $row = 1, $sheetName = '')
    {
        try {
            return $this->getSheet($sheetName)->setCellValue(
                $this->getCoordinate($column, $row),
                $value,
                true
            );
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Set excel file path property.
     *
     * @param string $filePath Excel file path parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If error raised when set mode.
     *
     * @return void
     */
    protected function setFilePath($filePath)
    {
        if (file_exists($filePath) === false) {
            $this->setMode('write');
        }
        $this->FilePath = $filePath;
        $this->setFileName(basename($this->FilePath));
    }

    /**
     * Set action mode property.
     *
     * @param string $mode Action mode parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid mode given.
     * @throws \Bridge\Components\Exporter\ExporterException If file cannot opened or not exists or not readable.
     *
     * @return void
     */
    protected function setMode($mode)
    {
        $validMode = ['write', 'read', 'update'];
        if (in_array(strtolower($mode), $validMode, true) === false) {
            throw new \Bridge\Components\Exporter\ExporterException('Invalid mode!');
        }
        $filePath = $this->getFilePath();
        if ((is_readable($filePath) === false or is_file($filePath) === false or file_exists($filePath) === false) and
            in_array($mode, ['read', 'update'], true) === true
        ) {
            throw new \Bridge\Components\Exporter\ExporterException(
                'Cannot set correct mode, please verify that the file can opened/exists/readable'
            );
        }
        $this->Mode = $mode;
    }

    /**
     * Validate the given styles array data that will be applied into cells.
     *
     * @param array $styles Styles array data parameter.
     *
     * @return boolean
     */
    protected function validateGridStyles(array $styles = [])
    {
        $validStyleKeys = array_keys(static::$ValidStyle);
        foreach ($validStyleKeys as $key) {
            $validStyleItemKeys = static::$ValidStyle[$key];
            if (array_key_exists($key, $styles) === true) {
                $givenStyleItemKeys = array_keys($styles[$key]);
                foreach ($givenStyleItemKeys as $givenKey) {
                    if (in_array($givenKey, $validStyleItemKeys, true) === false) {
                        return false;
                    }
                    $itemKey = array_search($givenKey, static::$ValidStyle[$key], true);
                    $validStyleItemValues = static::$ValidStyle[$key][$itemKey];
                    if (is_array($validStyleItemValues) === true and
                        in_array($styles[$key][$givenKey], $validStyleItemValues, true) === false
                    ) {
                        return false;
                    }
                }
            }
        }
        return true;
    }
}
