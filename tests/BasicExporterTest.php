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
    debug($matcherResult);
} catch (\Exception $ex) {
    throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
}
