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
namespace Bridge\Components\Exporter\WriterOptions;

/**
 * WriterOptionFactory class description.
 *
 * @package    Components
 * @subpackage Exporter\WriterOptions
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class ExcelWriterOptionFactory implements \Bridge\Components\Exporter\Contracts\FactoryInterface
{

    /**
     * Create the correct configurator.
     *
     * @param \PHPExcel_Writer_IWriter $objWriter Excel writer instance parameter.
     * @param array                    $options   Options data array parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If the option instance failed to create.
     * @return \Bridge\Components\Exporter\Contracts\ExcelWriterOptionInterface
     */
    public static function createOption(\PHPExcel_Writer_IWriter $objWriter, array $options = [])
    {
        $objWriterNameSpace = '\\Bridge\\Components\\Exporter\\WriterOptions\\';
        if ($objWriter instanceof \PHPExcel_Writer_Excel2007) {
            $objWriterFullName = $objWriterNameSpace . 'Excel2007WriterOption';
        } elseif ($objWriter instanceof \PHPExcel_Writer_CSV) {
            $objWriterFullName = $objWriterNameSpace . 'CsvWriterOption';
        } elseif ($objWriter instanceof \PHPExcel_Writer_Excel5) {
            $objWriterFullName = $objWriterNameSpace . 'Excel5WriterOption';
        } else {
            throw new \Bridge\Components\Exporter\ExporterException('Cannot create the options instance');
        }
        return new $objWriterFullName($objWriter, $options);
    }

    /**
     * Get factory name property.
     *
     * @return string
     */
    public function getFactoryName()
    {
        return 'ExcelWriterOptionFactory';
    }
}
