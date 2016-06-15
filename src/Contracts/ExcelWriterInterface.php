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
 * ExcelWriterInterface class description.
 *
 * @package    Components
 * @subpackage Exporter\Contracts
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
interface ExcelWriterInterface
{

    /**
     * Download the excel document.
     *
     * @param string $fileName   File name that will be set into downloaded file.
     * @param string $writerType Excel writer type that will be applied into php excel writer instance.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If content type of given writer set is not found.
     *
     * @return void
     */
    public function doDownload($fileName = '', $writerType = 'Excel2007');

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
    public function doSave($fileName = '', $writerType = 'Excel2007', array $grid = [], array $options = []);

    /**
     * Set the complete grid.
     *
     * @param array $grid The complete grid.
     *
     * @return void
     */
    public function setGrid(array $grid);
}
