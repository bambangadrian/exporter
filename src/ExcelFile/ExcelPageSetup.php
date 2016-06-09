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
 * ExcelPageSetup class description.
 *
 * @package    Components
 * @subpackage Exporter\ExcelFile
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class ExcelPageSetup extends \PHPExcel_Worksheet_PageSetup implements
    \Bridge\Components\Exporter\Contracts\ExcelPageElementInterface
{

    /**
     * Printing option name property.
     *
     * @var string $OptionName
     */
    private $OptionName;

    /**
     * ExcelPageSetup constructor.
     */
    public function __construct()
    {
        $this->OptionName = 'Page setup option';
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
