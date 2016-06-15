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
$data = [
    'test'  => [
        [
            'testfield1' => 'testfield1-row1',
            'testfield2' => 'testfield2-row1',
            'testfield3' => 'testfield3-row1',
            'testfield4' => 'testfield4-row1'
        ],
        [
            'testfield1' => 'testfield1-row2',
            'testfield3' => 'testfield3-row2',
            'testfield4' => 'testfield4-row2',
            'testfield5' => 'testfield5-row2'
        ],
        [
            'testfield1' => 'testfield1-row3',
            'testfield2' => 'testfield2-row3',
            'testfield3' => 'testfield3-row3',
            'testfield4' => 'testfield4-row3',
            'testfield5' => 'testfield5-row3'
        ],
        [
            'testfield2' => 'testfield2-row4',
            'testfield3' => 'testfield3-row4',
            'testfield4' => 'testfield4-row4',
            'testfield5' => 'testfield5-row4'
        ]
    ],
    'test2' => [
        [
            'test2field1' => 'test2field1-row1',
            'test2field2' => 'test2field2-row1',
            'test2field3' => 'test2field3-row1',
            'test2field4' => 'test2field4-row1',
            'test2field5' => 'test2field5-row1'
        ],
        [
            'test2field1' => 'test2field1-row2',
            'test2field2' => 'test2field2-row2',
            'test2field3' => 'test2field3-row2',
            'test2field4' => 'test2field4-row2',
            'test2field5' => 'test2field5-row2'
        ]
    ]
];
$arrayDataSource = new \Bridge\Components\Exporter\ArrayDataSource($data);
debug($arrayDataSource->getDataSourceHandler(), true);
//$arrayDataSource->setLoadedEntities(['test2']);
//$arrayDataSource->doLoad();
//debug($arrayDataSource->getData());
$arrayEntityBuilder = new \Bridge\Components\Exporter\TableEntityBuilder($arrayDataSource);
# Build the entities.
$arrayEntityBuilder->doBuild();
# Get specific table entity instance.
$arrayEntity = $arrayEntityBuilder->getEntity('test2');
debug($arrayEntity);
