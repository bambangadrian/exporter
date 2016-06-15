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
 * TableEntity class description.
 *
 * @package    Components
 * @subpackage Exporter
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
class TableEntity extends \Bridge\Components\Exporter\AbstractEntity implements
    \Bridge\Components\Exporter\Contracts\TableEntityInterface,
    \Bridge\Components\Exporter\Contracts\ExporterSubjectInterface
{

    /**
     * The exporter observer instance collection.
     *
     * @var \Bridge\Components\Exporter\Contracts\ExporterObserverInterface[] $Observers
     */
    protected $Observers = [];

    /**
     * Constraint entity instance property.
     *
     * @var \Bridge\Components\Exporter\Contracts\ConstraintEntityInterface
     */
    private $ConstraintEntity;

    /**
     * Message state property.
     *
     * @var string $EventMessage
     */
    private $EventMessage;

    /**
     * Event name property.
     *
     * @var string $EventName
     */
    private $EventName;

    /**
     * Event state property.
     *
     * @var integer $EventState
     */
    private $EventState;

    /**
     * TableEntity constructor.
     *
     * @param string                              $entityName          Entity name parameter.
     * @param Contracts\DataSourceInterface       $dataSourceObj       Data source instance parameter.
     * @param Contracts\ConstraintEntityInterface $constraintEntityObj Constraint entity object parameter.
     */
    public function __construct(
        $entityName,
        Contracts\DataSourceInterface $dataSourceObj,
        Contracts\ConstraintEntityInterface $constraintEntityObj = null
    ) {
        if ($constraintEntityObj !== null) {
            $this->setConstraintEntityObject($constraintEntityObj);
            $constraintEntityObj->validateTableEntityData($this);
        }
        parent::__construct($entityName, $dataSourceObj);
    }

    /**
     * Attach an observer.
     *
     * @param \Bridge\Components\Exporter\Contracts\ExporterObserverInterface $exporterObserver The observer parameter.
     *
     * @return void
     */
    public function attachObserver(\Bridge\Components\Exporter\Contracts\ExporterObserverInterface $exporterObserver)
    {
        if (in_array($exporterObserver, $this->Observers, true) === false) {
            $this->Observers[] = $exporterObserver;
        }
    }

    /**
     * Detach an observer.
     *
     * @param \Bridge\Components\Exporter\Contracts\ExporterObserverInterface $exporterObserver The observer parameter.
     *
     * @return void
     */
    public function detachObserver(\Bridge\Components\Exporter\Contracts\ExporterObserverInterface $exporterObserver)
    {
        if (($observerIndex = array_search($exporterObserver, $this->getAllObservers(), true)) !== false) {
            unset($this->Observers[$observerIndex]);
        }
    }

    /**
     * Delete the selected table entity record row.
     *
     * @param array $conditions Condition to select the specific row.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If searched field column does not exists.
     *
     * @return void
     */
    public function doDeleteRow(array $conditions)
    {
        $deletedRowIndexes = $this->getRowIndex($conditions);
        foreach ($deletedRowIndexes as $rowIndex) {
            unset($this->Data[$rowIndex]);
        }
    }

    /**
     * Do import entity.
     *
     * @param \Bridge\Components\Exporter\Contracts\TableEntityInterface $exportedEntity Exported entity parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid exported entity data structure found.
     *
     * @return void
     */
    public function doImport(\Bridge\Components\Exporter\Contracts\TableEntityInterface $exportedEntity)
    {
        $entityHandler = $this->getEntityHandler();
        $exportedData = $exportedEntity->getData();
        $this->setEventName('importEntity');
        $targetColumns = $this->getFields();
        foreach ($exportedData as $item) {
            # Formatted the exported data so it will be automatically filled the empty column.
            foreach ($targetColumns as $fieldObj) {
                $fieldName = $fieldObj->getFieldName();
                if (array_key_exists($fieldName, $item) === false and
                    $fieldObj->isPrimaryKey() === false and
                    $fieldObj->isAutoIncrement() === false
                ) {
                    $item[$fieldName] = null;
                    if ($fieldObj->isRequired()) {
                        $item[$fieldName] = $fieldObj->getFieldTypeObject()->getDefaultValue();
                    }
                }
            }
            $success = true;
            if ($entityHandler->getHandlerName() !== 'array' and
                $entityHandler->addImportedRow($item, $this->getName()) === false
            ) {
                $success = false;
            }
            if ($success === true) {
                $this->doInsertRow($item);
                $eventMessage = 'Inserting row data to table entity # ' . serialize($item);
                $eventState = \Bridge\Components\Exporter\Contracts\ExporterInterface::STATE_SUCCESS;
            } else {
                $eventMessage = 'Failed to insert row data to table entity #' . serialize($item);
                $eventState = \Bridge\Components\Exporter\Contracts\ExporterInterface::STATE_FAILED;
            }
            $this->setEventMessage($eventMessage);
            $this->setEventState($eventState);
            $this->doNotifyToAllObserver();
        }
    }

    /**
     * Insert new table entity record row from data collection.
     *
     * @param array $data Data that will be inserted.
     *
     * @return void
     */
    public function doInsertRow(array $data)
    {
        $fields = array_keys($this->getFields());
        $this->Data[] = array_merge(array_fill_keys($fields, null), $data);
    }

    /**
     * Notify all the observers that has been registered.
     *
     * @return void
     */
    public function doNotifyToAllObserver()
    {
        $observerObjCollection = $this->getAllObservers();
        foreach ($observerObjCollection as $observer) {
            $observer->doReceiveUpdateFromSubject($this);
        }
    }

    /**
     * Update table entity record row.
     *
     * @param array $data       Data that will be updated.
     * @param array $conditions Condition to select the specific row.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If searched field column does not exists.
     *
     * @return void
     */
    public function doUpdateRow(array $data, array $conditions)
    {
        $updatedRowIndexes = $this->getRowIndex($conditions);
        foreach ($updatedRowIndexes as $rowIndex) {
            foreach ($data as $column => $value) {
                $this->Data[$rowIndex][$column] = $value;
            }
        }
    }

    /**
     * Get all observers that has been assigned to the exporter subject instance.
     *
     * @return \Bridge\Components\Exporter\Contracts\ExporterObserverInterface[]
     */
    public function getAllObservers()
    {
        return $this->Observers;
    }

    /**
     * Get the constraint entity object as the table entity constraint data property.
     *
     * @return \Bridge\Components\Exporter\Contracts\ConstraintEntityInterface
     */
    public function getConstraintEntityObject()
    {
        return $this->ConstraintEntity;
    }

    /**
     * Get message state property.
     *
     * @return string
     */
    public function getEventMessage()
    {
        return $this->EventMessage;
    }

    /**
     * Get event name property.
     *
     * @return string
     */
    public function getEventName()
    {
        return $this->EventName;
    }

    /**
     * Get event state property.
     *
     * @return integer
     */
    public function getEventState()
    {
        return $this->EventState;
    }

    /**
     * Get exporter subject name property.
     *
     * @return string
     */
    public function getSubjectName()
    {
        return $this->getName();
    }

    /**
     * Check if the table entity has a constraint.
     *
     * @return boolean
     */
    public function hasConstraint()
    {
        return ($this->getConstraintEntityObject() !== null);
    }

    /**
     * Set the constraint entity object property.
     *
     * @param \Bridge\Components\Exporter\Contracts\ConstraintEntityInterface|null $constraintEntityObj Constraint
     *                                                                                                  entity object
     *                                                                                                  parameter.
     *
     * @return void
     */
    public function setConstraintEntityObject(
        \Bridge\Components\Exporter\Contracts\ConstraintEntityInterface $constraintEntityObj = null
    ) {
        $this->ConstraintEntity = $constraintEntityObj;
    }

    /**
     * Get row index number on table entity data collection that match the given condition.
     *
     * @param array $conditions Condition to select the specific row.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If searched field column does not exists.
     *
     * @return array
     */
    protected function getRowIndex(array $conditions)
    {
        $data = $this->getData();
        $fields = array_keys($this->getFields());
        $rowIndexes = [];
        foreach ($data as $rowIndex => $row) {
            $found = false;
            foreach ($conditions as $conditionField => $conditionValue) {
                if (in_array($conditionField, $fields, true) === false) {
                    throw new \Bridge\Components\Exporter\ExporterException(
                        'Searched field column does not exists: ' . $conditionField
                    );
                }
                if (in_array($conditionField, $fields, true) === true and
                    $row[$conditionField] !== $conditionValue
                ) {
                    $found = false;
                    break;
                }
                $found = true;
            }
            if ($found === true) {
                $rowIndexes[] = $rowIndex;
            }
        }
        return $rowIndexes;
    }

    /**
     * Set message state property.
     *
     * @param string $message Message parameter.
     *
     * @return void
     */
    protected function setEventMessage($message)
    {
        $this->EventMessage = $message;
    }

    /**
     * Set event name property.
     *
     * @param string $eventName Event name parameter.
     *
     * @return void
     */
    protected function setEventName($eventName)
    {
        $this->EventName = $eventName;
    }

    /**
     * Set event state property.
     *
     * @param integer $eventState Event state parameter.
     *
     * @return void
     */
    protected function setEventState($eventState)
    {
        $this->EventState = $eventState;
    }
}
