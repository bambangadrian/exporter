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
     * Constraint data property.
     *
     * @var \Bridge\Components\Exporter\ConstraintEntity[] $ConstraintEntities
     */
    protected $ConstraintEntities;

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
        $this->getDatabaseHandlerObject()->doRead($this->getLoadedEntities());
        try {
            $this->setData($this->getDatabaseHandlerObject()->getData());
            $this->setFields($this->getDatabaseHandlerObject()->getFields());
        } catch (\Exception $ex) {
            throw new \Bridge\Components\Exporter\ExporterException($ex->getMessage());
        }
        if (count($this->getData()) > 1) {
            $this->setMultipleSource(true);
        }
    }

    /**
     * Get constraint data property.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If failed to build constraint entities.
     *
     * @return \Bridge\Components\Exporter\ConstraintEntity[]
     */
    public function getConstraintEntities()
    {
        if ($this->ConstraintEntities === null or count($this->ConstraintEntities) === 0) {
            $this->doBuildConstraintEntities();
        }
        return $this->ConstraintEntities;
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
     * Do build the constraint entities for database.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If any error raised when init the instance.
     *
     * @return void
     */
    private function doBuildConstraintEntities()
    {
        $tableList = array_keys($this->getData());
        $dbHandler = $this->getDatabaseHandlerObject();
        $dbSchemaHandler = $dbHandler->getSchemaManagerObject();
        $constraintEntities = [];
        # Fetch all the fields from table list.
        foreach ($tableList as $entityName) {
            $constraintEntityObj = new \Bridge\Components\Exporter\ConstraintEntity($entityName, $this);
            $columnCollection = $dbSchemaHandler->listTableColumns(
                $entityName,
                $dbHandler->getDatabaseConnectionObject()->getDatabase()
            );
            foreach ($columnCollection as $columnObj) {
                # Parse the field constraint from entity array.
                $constraints = [
                    'required'      => $columnObj->getNotnull(),
                    'fieldTypeData' => [
                        'type'   => $dbHandler->getMappedFieldType($columnObj->getType()->getName()),
                        'length' => $columnObj->getLength()
                    ]
                ];
                # Create the field element object and assign the field element into the constraint entity.
                $fieldObj = new \Bridge\Components\Exporter\FieldElement($columnObj->getName(), $constraints);
                $constraintEntityObj->addField($fieldObj);
            }
            $constraintEntities[$entityName] = $constraintEntityObj;
        }
        $this->ConstraintEntities = $constraintEntities;
    }
}
