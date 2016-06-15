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
 * ExcelEntityFieldsReadFilter class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class ExcelEntityFieldsReadFilter extends \Bridge\Components\Exporter\AbstractExcelReadFilter
{

    /**
     * ExcelEntityFieldsReadFilter constructor.
     *
     * @param integer $rowNumber Field row number parameter.
     * @param array   $columns   Column range data parameter.
     * @param string  $sheetName Sheet name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If multiple number of row that given.
     */
    public function __construct($rowNumber = 1, array $columns = [], $sheetName = '')
    {
        if ($sheetName !== null and trim($sheetName) !== '') {
            $this->setSheetName($sheetName);
        }
        parent::__construct($rowNumber, $rowNumber, $columns);
    }
}
