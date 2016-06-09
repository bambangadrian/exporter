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
namespace Bridge\Components\Exporter\WriterOptions;

/**
 * AbstractExcelWriterOption class description.
 *
 * @package    Components
 * @subpackage Exporter\WriterOptions
 * @author     Bambang Adrian Sitompul <bambang.adrian@gmail.com>
 * @copyright  2016 -
 * @release    $Revision$
 */
abstract class AbstractExcelWriterOption implements \Bridge\Components\Exporter\Contracts\ExcelWriterOptionInterface
{

    /**
     * Options data property
     *
     * @var array $Options
     */
    protected $Options = [];

    /**
     * Writer instance property.
     *
     * @var \PHPExcel_Writer_IWriter $Writer
     */
    protected $Writer;

    /**
     * Valid option for writer object property.
     *
     * @var array $ValidOptions
     */
    protected static $ValidOptions = [];

    /**
     * AbstractExcelWriterOption constructor.
     *
     * @param \PHPExcel_Writer_IWriter $objWriter Writer instance parameter.
     * @param array                    $options   Options data parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid writer options given.
     */
    public function __construct(\PHPExcel_Writer_IWriter $objWriter, array $options = [])
    {
        $this->Writer = $objWriter;
        $this->setOptions($options);
    }

    /**
     * Get options data property.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->Options;
    }

    /**
     * Get valid option list array data.
     *
     * @return array
     */
    public function getValidOptionLists()
    {
        return static::$ValidOptions;
    }

    /**
     * Get writer object property.
     *
     * @return \Bridge\Components\Exporter\Contracts\ExcelWriterInterface
     */
    public function getWriterObject()
    {
        return $this->Writer;
    }

    /**
     * Run or applied the option configuration to the writer object.
     *
     * @return void
     */
    public function runConfigurator()
    {
        $options = $this->getOptions();
        foreach ($options as $key => $value) {
            $this->Writer->{static::$ValidOptions[$key]['callback']}($value);
        }
    }

    /**
     * Set the excel writer options by passing the options data parameter.
     *
     * @param array $options Writer option data collection parameter.
     *
     * @throws \Bridge\Components\Exporter\ExporterException If invalid writer options given.
     *
     * @return void
     */
    public function setOptions(array $options = [])
    {
        if ($this->validateOptions($options) === true) {
            $this->Options = $options;
        } else {
            throw new \Bridge\Components\Exporter\ExporterException('Invalid writer options given');
        }
    }

    /**
     * Validate the option value that given.
     *
     * @param string $optionName  The option name parameter that will be checked.
     * @param mixed  $optionValue The option value parameter that will be checked.
     *
     * @return boolean
     */
    protected function validateOptionValue($optionName, $optionValue)
    {
        if (array_key_exists('valueCheck', static::$ValidOptions[$optionName]) === true and
            trim(static::$ValidOptions[$optionName]['valueCheck']) !== '' and
            static::$ValidOptions[$optionName]['valueCheck'] !== null
        ) {
            return $this->{static::$ValidOptions[$optionName]['valueCheck']}($optionValue);
        }
        return true;
    }

    /**
     * Validate the given options data.
     *
     * @param array $options Options data parameter.
     *
     * @return boolean
     */
    protected function validateOptions(array $options = [])
    {
        $optionKeys = array_keys($options);
        $validOptions = array_keys(static::$ValidOptions);
        foreach ($optionKeys as $optionName) {
            if (in_array($optionName, $validOptions, true) === false) {
                return false;
            }
            if ($this->validateOptionValue($optionName, $options[$optionName]) === false) {
                return false;
            }
        }
        return true;
    }
}
