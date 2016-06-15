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
 * ArrayHandler class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class ArrayHandler extends \ArrayObject implements \Bridge\Components\Exporter\Contracts\DataSourceHandlerInterface
{

    /**
     * Data collection property.
     *
     * @var array $Data
     */
    private $Data;

    /**
     * Add one row to the selected entity name on data source.
     *
     * @param array  $data       Complete array for one row to add.
     * @param string $entityName Entity name parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If any error raised when adding/saving the row data.
     *
     * @return boolean
     */
    public function addImportedRow(array $data, $entityName = '')
    {
        $this->Data[$entityName][] = $data;
        return true;
    }

    /**
     * Get handler name property.
     *
     * @return string
     */
    public function getHandlerName()
    {
        return 'array';
    }
}
