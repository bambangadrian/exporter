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
try {
    $postgreSqlDriver = new \Bridge\Components\Database\Adapter\PostgreSqlAdapter();
    $dbSourceObject = new \Bridge\Components\Exporter\DbDataSource();
    $dbSourceObject->setDriver($postgreSqlDriver);
    $dbTableEntityBuilder = new \Bridge\Components\Exporter\TableEntityBuilder($dbSourceObject);
    $dbSourceObject->doMassImport($matcherResult);
} catch (\Exception $ex) {
    throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
}
