<?php
/**
 * Code written is strictly used within this program.
 * Any other use of this code is in violation of copy rights.
 *
 * @package   Components
 * @author    Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright 2016 Developer
 * @license   - No License
 * @version   GIT: $Id$
 * @link      -
 */
namespace Bridge\Components\Exporter;

/**
 * DbDataSource class description (Only connect via postgres dbms for now).
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class DbDataSource extends \Bridge\Components\Exporter\AbstractDataSource
{

    /**
     * Database adapter that will be required to open connection to server.
     *
     * @var \Bridge\Components\Exporter\Contracts\DatabaseHandlerInterface $DatabaseHandler
     */
    protected $DatabaseHandler;

    /**
     * DbDataSource constructor.
     *
     * @param \Bridge\Components\Exporter\Contracts\DatabaseHandlerInterface $dbHandlerObj Database handler object.
     */
    public function __construct(\Bridge\Components\Exporter\Contracts\DatabaseHandlerInterface $dbHandlerObj)
    {
        $this->DatabaseHandler = $dbHandlerObj;
    }

    /**
     * Load the data source and run initial process.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If failed to retrieve database information.
     *
     * @return void
     */
    public function doLoad()
    {
        $this->getDatabaseHandlerObject()->doRead();
        try {
            $this->setData($this->DatabaseHandler->getData());
            $this->setFields($this->DatabaseHandler->getFields());
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
    }

    /**
     * Do mass import data set.
     *
     * @param array $data Data that will be updated into data source.
     *
     * @return void
     */
    public function doMassImport(array $data)
    {
        if ($this->validateImportData($data) === true) {

        }
    }

    /**
     * Get database handler object.
     *
     * @return \Bridge\Components\Exporter\Contracts\DatabaseHandlerInterface
     */
    public function getDatabaseHandlerObject()
    {
        return $this->DatabaseHandler;
    }

    /**
     * Validate import data before saving into database.
     *
     * @param array $data Import data collection parameter.
     *
     * @return boolean
     */
    private function validateImportData($data)
    {
        return true;
    }
}
