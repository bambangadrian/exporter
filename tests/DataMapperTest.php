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
# This test case will be provide mapper hrd_bank sheet document process sample.
# -------------------------------------------------------------------------------------------------------------
# Data source decorator mock-up for constraint.
$constraintDataSource = new \Bridge\Components\Exporter\ExcelDataSource(
    '../resources/files/Constraints/MasterData.xlsx'
);
$constraintDataSource->setLoadedEntities(['hrd_bank']);
$constraintBuilder = new \Bridge\Components\Exporter\ConstraintEntityBuilder($constraintDataSource);
# Set the field mapper to constraint entity builder.
$fieldMapper = [
    'fieldName'   => 'Name Field',
    'required'    => 'Required',
    'fieldType'   => 'Field Type',
    'fieldLength' => 'Field Length'
];
# Set the field type mapper to constraint entity builder.
$constraintBuilder->setFieldMapper($fieldMapper);
$fieldTypeMapper = [
    'Character'   => 'string',
    'Numeric'     => 'number',
    'Enumeration' => 'enum',
    'Date'        => 'date',
    'Boolean'     => 'boolean'
];
$constraintBuilder->setFieldTypeMapper($fieldTypeMapper);
# Build the constraint entities.
$constraintBuilder->doBuild();
# Get the constraints entities.
$constraintEntities = $constraintBuilder->getEntities();
# Get specific constraint entity instance.
$constraintEntityObject = $constraintBuilder->getEntity('hrd_bank');
# -------------------------------------------------------------------------------------------------------------
# Mock-up for excel data master object.
$sourceData = new \Bridge\Components\Exporter\ExcelDataSource(
    '../resources/files/Master Data/hrd_bank.xlsx'
);
$sourceData->setLoadedEntities(['hrd_bank']);
# Mock-up for table entity builder.
$entitySourceBuilder = new \Bridge\Components\Exporter\TableEntityBuilder($sourceData, $constraintEntities);
$fieldConstraintMapper = [
    'hrd_bank' => [
        'Bank Code' => 'bank_code',
        'Bank Name' => 'bank_name'
    ]
];
$entitySourceBuilder->setFieldConstraintMapper($fieldConstraintMapper);
# Build the table entities.
$entitySourceBuilder->doBuild();
# Get specific table entity instance.
$entitySourceObject = $entitySourceBuilder->getEntity('hrd_bank');
# -------------------------------------------------------------------------------------------------------------
# Set target data source object.
$targetData = new \Bridge\Components\Exporter\ExcelDataSource(
    '../resources/files/Customer Data/hrd_bank_customer.xlsx'
);
$targetData->setLoadedEntities(['hrd_bank']);
$targetData->setFieldReadFilter(4);
# Create the entity target builder object.
$entityTargetBuilder = new \Bridge\Components\Exporter\TableEntityBuilder($targetData);
# Build the target entities.
$entityTargetBuilder->doBuild();
# Get specific table entity instance.
$entityTargetObject = $entityTargetBuilder->getEntity('hrd_bank');
# -------------------------------------------------------------------------------------------------------------
# Create the mapper instance.
$mapperObject = new \Bridge\Components\Exporter\EntityMapper();
# Set the source entity instance.
$mapperObject->setSourceEntity($entitySourceObject);
# Set the target entity instance that will be mapped.
$mapperObject->setTargetEntity($entityTargetObject);
# Set the field mapper data between source and target entity.
$fieldMapperData = [
    'Code' => 'bank_code',
    'Name' => 'bank_name'
];
$mapperObject->setFieldMapperData($fieldMapperData);
# Run the mapper procedure.
$mapperObject->doMapping();
# Get the data mapper result.
$dataMapperResult = $mapperObject->getMappedData();
$mapperResultEntity = $mapperObject->getResultEntityObject();
//debug($mapperObject->getResultEntityObject());
//debug($dataMapperResult);
