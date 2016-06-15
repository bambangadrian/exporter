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
 * Excel5WriterOption class description.
 *
 * @package    Components
 * @subpackage Exporter\WriterOptions
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class Excel5WriterOption extends \Bridge\Components\Exporter\WriterOptions\AbstractExcelWriterOption
{

    /**
     * Excel5WriterOption constructor.
     *
     * @param \PHPExcel_Writer_IWriter $objWriter Excel writer instance parameter.
     * @param array                    $options   Options data parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid writer object given.
     */
    public function __construct(\PHPExcel_Writer_IWriter $objWriter, array $options = [])
    {
        if ($objWriter instanceof \PHPExcel_Writer_Excel5) {
            static::$ValidOptions = [];
            parent::__construct($objWriter, $options);
        } else {
            throw new \Bridge\Components\Exporter\ExporterException('Invalid writer object given');
        }
    }
}
