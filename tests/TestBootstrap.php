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
include_once '../vendor/autoload.php';
if (function_exists('debug') === false) {
    /**
     * Debugging method, just for development phase only.
     *
     * @param mixed   $var  Variable that want to be dumped.
     * @param boolean $exit Set the exit option parameter.
     *
     * @return void
     */
    function debug($var, $exit = false)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        if ($exit === true) {
            exit;
        }
    }
}
$arr = ['dataBambang', 'Data Bambang', 'data Bambang'];
