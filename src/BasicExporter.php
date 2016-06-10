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
 * BasicExporter class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class BasicExporter implements \Bridge\Components\Exporter\Contracts\ExporterInterface
{

    /**
     * Exporter log data property.
     *
     * @var array $Log
     */
    protected $Log;

    /**
     * Exported Source data array property.
     *
     * @var array $ExportedData
     */
    private $ExportedData;

    /**
     * Exporter status property.
     *
     * @var self::STATE_ERROR|self::STATE_FAILED|self::STATE_SUCCESS $Status
     */
    private $Status;

    /**
     * Target entity object property.
     *
     * @var \Bridge\Components\Exporter\Contracts\TableEntityInterface $TargetEntity
     */
    private $TargetEntity;

    /**
     * Required log key array data property.
     *
     * @var array $RequiredLogKey
     */
    private static $RequiredLogKey = ['message', 'time', 'code'];

    /**
     * BasicExporter constructor.
     *
     * @param array                                                      $exportedData    Exported data array parameter.
     * @param \Bridge\Components\Exporter\Contracts\TableEntityInterface $targetEntityObj Data target object parameter.
     */
    public function __construct(
        array $exportedData = [],
        \Bridge\Components\Exporter\Contracts\TableEntityInterface $targetEntityObj = null
    ) {
        $this->setExportedData($exportedData);
        if ($targetEntityObj !== null) {
            $this->setTargetEntity($targetEntityObj);
        }
    }

    /**
     * Do export the source data to target.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If target entity object still not assigned.
     *
     * @return void
     */
    public function doExport()
    {
        if ($this->getTargetEntityObject() === null) {
            throw new \Bridge\Components\Exporter\ExporterException('Please assign the target object to the exporter');
        }
        $exportedData = $this->getExportedData();
        foreach ($exportedData as $row) {
            $this->getTargetEntityObject()->doInsertRow($row);
        }
        $this->getTargetEntityObject()->doSave();
    }

    /**
     * Get exported data property
     *
     * @return array
     */
    public function getExportedData()
    {
        return $this->ExportedData;
    }

    /**
     * Get exporter handler instance.
     *
     * @return \Bridge\Components\Exporter\Contracts\ExporterHandlerInterface
     */
    public function getExporterHandlerObject()
    {
        //return $this->getTargetEntityObject()->getDataSourceObject()
    }

    /**
     * Get log data property.
     *
     * @return array
     */
    public function getLog()
    {
        return $this->Log;
    }

    /**
     * Get exporter status.
     *
     * @return self::STATE_ERROR|self::STATE_FAILED|self::STATE_SUCCESS
     */
    public function getStatus()
    {
        return $this->Status;
    }

    /**
     * Get exporter target entity instance property.
     *
     * @return \Bridge\Components\Exporter\Contracts\TableEntityInterface
     */
    public function getTargetEntityObject()
    {
        return $this->TargetEntity;
    }

    /**
     * Set exported data array as source.
     *
     * @param array $data Source data array parameter.
     *
     * @return void
     */
    public function setExportedData(array $data)
    {
        $this->ExportedData = $data;
    }

    /**
     * Set exporter target object property.
     *
     * @param \Bridge\Components\Exporter\Contracts\TableEntityInterface $targetEntityObj Target entity parameter.
     *
     * @return void
     */
    public function setTargetEntity(\Bridge\Components\Exporter\Contracts\TableEntityInterface $targetEntityObj)
    {
        $this->TargetEntity = $targetEntityObj;
    }

    /**
     * Add log data into log data array property.
     *
     * @param array $logData Log data array parameter.
     *
     * @return void
     */
    protected function addLog(array $logData)
    {
        if ($this->validateLogItem($logData) === true) {
            $this->Log[] = $logData;
        }
    }

    /**
     * Set exporter status property.
     *
     * @param self ::STATE_ERROR|self::STATE_FAILED|self::STATE_SUCCESS $status Exporter status parameter.
     *
     * @return void
     */
    protected function setStatus($status)
    {
        $this->Status = $status;
    }

    /**
     * Validate the log item data that want to be added into exporter log property.
     *
     * @param array $logData Log data array parameter.
     *
     * @return boolean
     */
    private function validateLogItem(array $logData)
    {
        foreach (static::$RequiredLogKey as $requiredKey) {
            if (array_key_exists($requiredKey, $logData) === false) {
                return false;
            }
        }
        return true;
    }
}
