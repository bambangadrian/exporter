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
 * ExcelReaderInterface class description.
 *
 * @package    Components
 * @subpackage Exporter\Contracts
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
interface ExcelReaderInterface
{

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
    public function doPrinting(array $options = [], $sheetName = '');

    /**
     * Load and read excel file.
     *
     * @param \Bridge\Components\Exporter\Contracts\ExcelReadFilterInterface $readFilter Excel read filter parameter.
     * @param array                                                          $sheetNames Sheet name data collection
     *                                                                                   parameter.
     * @param string                                                         $readerType Excel reader type parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If any error raised when read the excel content.
     *
     * @return void
     */
    public function doRead(
        \Bridge\Components\Exporter\Contracts\ExcelReadFilterInterface $readFilter = null,
        array $sheetNames = [],
        $readerType = 'Excel2007'
    );

    /**
     * Get excel file data property.
     *
     * @return array
     */
    public function getData();

    /**
     * Set read filter object property.
     *
     * @param \Bridge\Components\Exporter\Contracts\ExcelReadFilterInterface $readFilter Excel read filter parameter.
     *
     * @return void
     */
    public function setReadFilter(\Bridge\Components\Exporter\Contracts\ExcelReadFilterInterface $readFilter);
}
