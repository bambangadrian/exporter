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
 * ExcelEntityRecordReadFilter class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class ExcelEntityRecordReadFilter extends \Bridge\Components\Exporter\AbstractExcelReadFilter
{

    /**
     * ExcelEntityFieldsReadFilter constructor.
     *
     * @param \Bridge\Components\Exporter\ExcelEntityFieldsReadFilter $readFilter Excel field filter object parameter.
     * @param integer                                                 $startRow   Start cell row number parameter.
     * @param integer                                                 $endRow     End cell row number parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException Invalid start cell row number given.
     */
    public function __construct(
        \Bridge\Components\Exporter\ExcelEntityFieldsReadFilter $readFilter,
        $startRow = null,
        $endRow = null
    ) {
        if ($startRow === null) {
            $startRow = $readFilter->getStartRow() + 1;
        }
        if ($startRow <= $readFilter->getStartRow()) {
            throw new \Bridge\Components\Exporter\ExporterException('Invalid start cell row number given');
        }
        $this->setSheetName($readFilter->getSheetName());
        parent::__construct($startRow, $endRow, $readFilter->getColumns());
    }
}
