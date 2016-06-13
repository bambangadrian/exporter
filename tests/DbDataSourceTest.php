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
include_once 'TestBootstrap.php';
$connectionConfig = [
    'dbname'   => 'devosa',
    'user'     => 'postgres',
    'password' => 'postgres',
    'host'     => 'localhost'
];
$dbHandler = new \Bridge\Components\Exporter\Database\PostgreSqlHandler($connectionConfig);
//$dbHandler->executeQuery('insert into "hrd_bank" (bank_code, bank_name) VALUES (\'test\', \'test\')');
$dbDataSource = new \Bridge\Components\Exporter\DbDataSource($dbHandler);
//debug($dbDataSource);
$dbDataSource->setLoadedEntities(['hrd_bank']);
//debug($dbDataSource->getLoadedEntities());
# Create the entity target builder object.
$dbEntityBuilder = new \Bridge\Components\Exporter\TableEntityBuilder(
    $dbDataSource,
    $dbDataSource->getConstraintEntities()
);
# Build the target entities.
$dbEntityBuilder->doBuild();
//debug($dbHandler->getTables());
//debug($dbEntityBuilder->getEntities());
# Get specific table entity instance.
$dbEntity = $dbEntityBuilder->getEntity('hrd_bank');
//$dbEntity->doInsertRow(['bank_code' => 'test']);
//$dbEntity->doUpdateRow(['bank_code' => 'BRA'], ['id' => 35]);
//debug($dbEntity->getData());
