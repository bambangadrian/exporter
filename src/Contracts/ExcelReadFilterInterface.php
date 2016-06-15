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
 * ExcelReadFilterInterface class description.
 *
 * @package    Components
 * @subpackage Exporter\Contracts
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
interface ExcelReadFilterInterface extends \PHPExcel_Reader_IReadFilter
{

    /**
     * Read cell filter.
     *
     * @param string  $column        Column name parameter.
     * @param integer $row           Cell row number parameter.
     * @param string  $workSheetName Work sheet name parameter.
     *
     * @return boolean
     */
    public function readCell($column, $row, $workSheetName = '');
}
