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
 * ExcelFile class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class ExcelFile
{

    /**
     * Php excel object property.
     *
     * @var \PHPExcel $PhpExcel
     */
    private $PhpExcel;

    /**
     * ExcelFile constructor.
     */
    public function __construct()
    {
        $this->PhpExcel = new \PHPExcel();
    }
}
