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
 * ExcelWriterOptionInterface class description.
 *
 * @package    Components
 * @subpackage Exporter\Contracts
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
interface ExcelWriterOptionInterface
{

    /**
     * Get writer object property.
     *
     * @return \Bridge\Components\Exporter\Contracts\ExcelWriterInterface
     */
    public function getWriterObject();

    /**
     * Run or applied the option configuration to the writer object.
     *
     * @return void
     */
    public function runConfigurator();

    /**
     * Set the excel writer options by passing the options data parameter.
     *
     * @param array $options Writer option data collection parameter.
     *
     * @return void
     */
    public function setOptions(array $options = []);
}
