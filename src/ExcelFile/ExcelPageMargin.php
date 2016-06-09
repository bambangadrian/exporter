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
namespace Bridge\Components\Exporter\ExcelFile;

/**
 * ExcelPageMargin class description.
 *
 * @package    Components
 * @subpackage Exporter\ExcelFile
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class ExcelPageMargin extends \PHPExcel_Worksheet_PageMargins implements
    \Bridge\Components\Exporter\Contracts\ExcelPageElementInterface
{

    /**
     * Printing option name property.
     *
     * @var string $OptionName
     */
    private $OptionName;

    /**
     * ExcelPageMargin constructor.
     */
    public function __construct()
    {
        $this->OptionName = 'Page margin option';
        parent::__construct();
    }

    /**
     * Get printing option name property.
     *
     * @return string
     */
    public function getPrintingOptionName()
    {
        return $this->OptionName;
    }
}
