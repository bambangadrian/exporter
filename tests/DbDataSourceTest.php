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
$dbDataSource = new \Bridge\Components\Exporter\DbDataSource($dbHandler);
$dbDataSource->setLoadedEntities(['hrd_employee']);
# Create the entity target builder object.
$dbEntityBuilder = new \Bridge\Components\Exporter\TableEntityBuilder(
    $dbDataSource,
    $dbDataSource->getConstraintEntities()
);
# Build the target entities.
$dbEntityBuilder->doBuild();
//debug($dbEntityBuilder->getEntities());
# Get specific table entity instance.
$dbEntity = $dbEntityBuilder->getEntity('hrd_employee');
debug($dbEntity->getData());
