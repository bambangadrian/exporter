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
    'dbname'   => 'exporter',
    'user'     => 'postgres',
    'password' => 'postgres',
    'host'     => 'localhost'
];
$dbHandler = new \Bridge\Components\Exporter\Database\PostgreSqlHandler($connectionConfig);
$dbDataSource = new \Bridge\Components\Exporter\DbDataSource($dbHandler);
$standardDataSource = new \Bridge\Components\Exporter\StandardDataSource($dbDataSource);
debug($standardDataSource->getData());
