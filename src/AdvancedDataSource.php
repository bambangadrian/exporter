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
 * AdvancedDataSource class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class AdvancedDataSource extends \Bridge\Components\Exporter\AbstractDataSourceDecorator
{

    /**
     * Convert set data property
     *
     * @var array $ConverterSet
     */
    private $ConverterSet;

    /**
     * Get the converter set data property.
     *
     * @return array
     */
    public function getConverterSet()
    {
        return $this->ConverterSet;
    }
}
