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
 * BaseDataSource class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class StandardDataSource extends \Bridge\Components\Exporter\AbstractDataSourceDecorator
{

    /**
     * Standard data result property.
     *
     * @var array $StandardData
     */
    private $StandardData;

    /**
     * Get standard data property.
     *
     * @return array
     */
    public function getStandardData()
    {
        return $this->StandardData;
    }
}
