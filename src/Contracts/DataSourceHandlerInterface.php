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
 * DataSourceHandlerInterface class description.
 *
 * @package    Components
 * @subpackage Exporter\Contracts
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
interface DataSourceHandlerInterface
{

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
    public function addImportedRow(array $data, $entityName = '');

    /**
     * Get handler name property.
     *
     * @return string
     */
    public function getHandlerName();
}
