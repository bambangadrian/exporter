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
     * Exported Source data array property.
     *
     * @var \Bridge\Components\Exporter\Contracts\TableEntityInterface $ExportedEntity
     */
    private $ExportedEntity;

    /**
     * Exporter log data property.
     *
     * @var array $Log
     */
    private $Log;

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
     * @param \Bridge\Components\Exporter\Contracts\TableEntityInterface $exportedEntityObj Exported data array
     *                                                                                      parameter.
     * @param \Bridge\Components\Exporter\Contracts\TableEntityInterface $targetEntityObj   Data target object
     *                                                                                      parameter.
     */
    public function __construct(
        \Bridge\Components\Exporter\Contracts\TableEntityInterface $exportedEntityObj,
        \Bridge\Components\Exporter\Contracts\TableEntityInterface $targetEntityObj
    ) {
        $this->setExportedEntity($exportedEntityObj);
        $this->setTargetEntity($targetEntityObj);
    }

    /**
     * Do export the source data to target.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If target entity object still not assigned.
     * @throws \Bridge\Components\Exporter\ExporterException If invalid exported entity data structure found.
     *
     * @return void
     */
    public function doExport()
    {
        if ($this->getTargetEntityObject() === null) {
            throw new \Bridge\Components\Exporter\ExporterException('Please assign the target object to the exporter');
        }
        # Format target entity as subject interface and table entity interface instance on the same time.
        $targetEntityObj = $this->getTargetEntityObject();
        if ($targetEntityObj instanceof \Bridge\Components\Exporter\Contracts\ExporterSubjectInterface) {
            # Attach exporter as observer to the called table entity.
            $targetEntityObj->attachObserver($this);
            # Start to import.
            $targetEntityObj->doImport($this->getExportedEntityObject());
        } else {
            throw new \Bridge\Components\Exporter\ExporterException('Invalid exporter subject found');
        }
    }

    /**
     * Get exported entity instance.
     *
     * @return \Bridge\Components\Exporter\Contracts\TableEntityInterface
     */
    public function getExportedEntityObject()
    {
        return $this->ExportedEntity;
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
     * Set exporter exported entity instance property.
     *
     * @param \Bridge\Components\Exporter\Contracts\TableEntityInterface $exportedEntityObj Exported entity parameter.
     *
     * @return void
     */
    public function setExportedEntity(\Bridge\Components\Exporter\Contracts\TableEntityInterface $exportedEntityObj)
    {
        $this->ExportedEntity = $exportedEntityObj;
    }

    /**
     * Set exporter target instance property.
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
     * Receive update from subject.
     *
     * @param \Bridge\Components\Exporter\Contracts\ExporterSubjectInterface $exporterSubject The Subject that
     *                                                                                        notifying the observer of
     *                                                                                        an update.
     *
     * @return void
     */
    public function update(\Bridge\Components\Exporter\Contracts\ExporterSubjectInterface $exporterSubject)
    {
        /**
         * Format the exporter subject as table entity.
         *
         * @var \Bridge\Components\Exporter\Contracts\TableEntityInterface $exporterSubject
         */
        $this->addLog(
            [
                'message' => $exporterSubject->getName(),
                'time'    => '',
                'code'    => \Bridge\Components\Exporter\Contracts\ExporterInterface::STATE_SUCCESS
            ]
        );
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
