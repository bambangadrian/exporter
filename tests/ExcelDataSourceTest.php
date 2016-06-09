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
try {
    # Data source decorator mock-up.
    $constraintDataSource = new \Bridge\Components\Exporter\ExcelDataSource(
        '../resources/files/Constraints/MasterData.xlsx'
    );
    //$constraintDataSource->doLoad();
    //debug($constraintDataSource->getData(['hrd_company']));
    //debug($constraintDataSource->getFields(), false);
    # Use decorator to format to standard data source.
    $baseDataSource = new \Bridge\Components\Exporter\StandardDataSource($constraintDataSource);
    # Mock-up for entities builder.
    $entityBuilder = new \Bridge\Components\Exporter\ConstraintEntityBuilder($baseDataSource);
    $fieldMapper = [
        'fieldName' => 'Name Field',
        'required' => 'Required',
        'fieldType' => 'Field Type',
        'fieldLength' => 'Field Length'
    ];
    $entityBuilder->setFieldMapper($fieldMapper);
    $fieldTypeMapper = [
        'string' => 'Character',
        'number' => 'Numeric',
        'enum'   => 'Enumeration',
        'date'   => 'Date'
    ];
    $entityBuilder->setFieldTypeMapper($fieldTypeMapper);
    # Build the entity.
    $entityBuilder->doBuild();
    $entityObject = $entityBuilder->getEntity('hrd_company');
    $entityData = $entityObject->getField('City');
    debug($entityData->getConstraints());
} catch (\Exception $ex) {
    throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
}
