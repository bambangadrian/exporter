<?php
/**
 * Contains code written by the Invosa Systems Company and is strictly used within this program.
 * Any other use of this code is in violation of copy rights.
 *
 * @package   -
 * @author    Bambang Adrian Sitompul <bambang@invosa.com>
 * @copyright 2016 Invosa Systems Indonesia
 * @license   http://www.invosa.com/license No License
 * @version   GIT: $Id$
 * @link      http://www.invosa.com
 */
include_once 'DataMapperTest.php';
include_once 'DbDataSourceTest.php';
debug($mapperResult);
# Mock-up for run the exporter process.
$exporter = new \Bridge\Components\Exporter\BasicExporter();
$exporter->setExportedData($mapperResult);
# Mock-up for exporter process.
$exporter->setTargetEntity($dbDataSource);
$exporter->doExport();
debug($exporter->getStatus());
debug($exporter->getLog());
