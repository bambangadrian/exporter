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
# Mock-up for excel file.
# Using read mode.
$excelFile = new \Bridge\Components\Exporter\BasicExcelFile('../resources/files/Constraints/MasterData.xlsx');
$excelFile->setLoadedSheets(['hrd_company']);
$fieldFilter = new \Bridge\Components\Exporter\ExcelEntityFieldsReadFilter(1, range('A', 'D'), 'hrd_company');
$rowFilter = new \Bridge\Components\Exporter\ExcelEntityRecordReadFilter($fieldFilter);
$excelFile->doRead($rowFilter);
$excelArrayContents = $excelFile->getData();
debug($excelArrayContents);
