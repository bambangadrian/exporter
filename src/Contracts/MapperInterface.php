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
 * MapperInterface class description.
 *
 * @package    Components
 * @subpackage Exporter\Contracts
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
interface MapperInterface
{

    /**
     * Run mapper procedure.
     *
     * @param boolean $replaceSourceData Replace all source data flag option parameter.
     * @param boolean $reIndex           Re-index all the mapper data result keys flag option parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If any error raised when run the mapper procedure.
     *
     * @return void
     */
    public function doMapping($replaceSourceData = true, $reIndex = true);

    /**
     * Get the mapper data property.
     *
     * @return array
     */
    public function getFieldMapperData();

    /**
     * Get mapped data result.
     *
     * @param array $fieldFilters Field filters data array parameter.
     *
     * @return array
     */
    public function getMappedData(array $fieldFilters = []);

    /**
     * Get the entity source object instance property.
     *
     * @return \Bridge\Components\Exporter\Contracts\EntityInterface
     */
    public function getSourceEntityObject();

    /**
     * Get the entity target object instance property.
     *
     * @return \Bridge\Components\Exporter\Contracts\EntityInterface
     */
    public function getTargetEntityObject();
}
