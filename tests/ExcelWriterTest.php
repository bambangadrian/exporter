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
# Using write mode.
$excelFile = new \Bridge\Components\Exporter\BasicExcelFile('../resources/files/Constraints/Test.xlsx');
$printOptions = [
    'orientation' => '',
    'paperSize'   => ''
];
$arrData = [
    'properties' => [
        'creator'        => '',
        'lastModifiedBy' => '',
        'title'          => '',
        'subject'        => '',
        'description'    => '',
        'keywords'       => '',
        'category'       => ''
    ],
    'worksheets' => [
        'Sheet No 1' => [
            'contents' => [
                2 => [
                    'data'   => ['field1', 'field2', 'field3', 'field4'],
                    'styles' => [
                        'font'      => ['bold' => true],
                        'borders'   => [
                            'top' => ['style' => \PHPExcel_Style_Border::BORDER_DOUBLE]
                        ],
                        'alignment' => ['horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT]
                    ],
                    'format' => []
                ],
                3 => ['data' => ['f1r1', 'f2r1', 'f3r1', 'f4r1'], 'styles' => [], 'format' => []],
                4 => ['data' => ['f1r2', 'f2r2', 'f3r2', 'f4r2'], 'styles' => [], 'format' => []],
                5 => ['data' => ['f1r3', 'f2r3', 'f3r3', 'f4r3'], 'styles' => [], 'format' => []],
                6 => ['data' => ['f1r1', 'f2r1', 'f3r1', 'f4r1'], 'styles' => [], 'format' => []]
            ]
        ],
        'Sheet2'     => [
            'contents' => [
                3 => ['data' => ['f1r1', 'f2r1', 'f3r1', 'f4r1'], 'styles' => [], 'format' => []],
                4 => ['data' => ['f1r2', 'f2r2', 'f3r2', 'f4r2'], 'styles' => [], 'format' => []],
                5 => ['data' => ['f1r3', 'f2r3', 'f3r3', 'f4r3'], 'styles' => [], 'format' => []]
            ]
        ]
    ]
];
$excelFile->setGrid($arrData);
$excelFile->addRow(['data' => ['f1r6', 'f2r6', 'f3r6', 'f4r6', 'f5r6']], 'Sheet2');
$excelFile->addRow(['data' => ['f1r6', 'f2r6', 'f3r6', 'f4r6', 'f5r6']], 'Sheet2');
$excelFile->doSave();
$excelFile->doRead();
debug($excelFile->getData());
//$excelFile->doDownload('test.xls', 'Excel5');
