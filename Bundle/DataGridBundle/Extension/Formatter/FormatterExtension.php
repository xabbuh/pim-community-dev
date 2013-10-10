<?php

namespace Oro\Bundle\DataGridBundle\Extension\Formatter;

use Oro\Bundle\DataGridBundle\Datasource\DatasourceInterface;
use Oro\Bundle\DataGridBundle\Extension\ExtensionVisitorInterface;
use Oro\Bundle\DataGridBundle\Extension\Formatter\Property\PropertyInterface;

class FormatterExtension implements ExtensionVisitorInterface
{
    const COLUMNS_KEY    = 'columns';
    const PROPERTIES_KEY = 'properties';

    /** @var PropertyInterface[] */
    protected $properties;

    /**
     * {@inheritDoc}
     */
    public function isApplicable(array $config)
    {
        return !empty($config[self::COLUMNS_KEY]) || !empty($config[self::PROPERTIES_KEY]);
    }

    /**
     * {@inheritDoc}
     */
    public function visitDatasource(array $config, DatasourceInterface $datasource)
    {
        // this extension do not affect source, do nothing
    }

    /**
     * {@inheritDoc}
     */
    public function visitResult(array $config, \stdClass $result)
    {
        $rows       = (array)$result->rows;
        $results    = array();
        $columns    = !empty($config[self::COLUMNS_KEY]) ? $config[self::COLUMNS_KEY] : array();
        $properties = !empty($config[self::PROPERTIES_KEY]) ? $config[self::PROPERTIES_KEY] : array();
        $toProcess  = array_merge($columns, $properties);

        foreach ($rows as $row) {
            $resultRecord = array();
            $record       = new ResultRecord($row);

            foreach ($toProcess as $name => $fieldConfig) {
                $property            = $this->getPropertyObject($name, $fieldConfig);
                $resultRecord[$name] = $property->getValue($record);
            }

            $results[] = array_merge($row, $resultRecord);
        }

        $result->rows = $results;
    }

    /**
     * Add property to array of available properties
     *
     * @param string            $name
     * @param PropertyInterface $property
     *
     * @return $this
     */
    public function addProperty($name, PropertyInterface $property)
    {
        $this->properties[$name] = $property;

        return $this;
    }

    /**
     * @param string $name
     * @param array  $config
     *
     * @throws \RuntimeException
     * @return PropertyInterface
     */
    protected function getPropertyObject($name, array $config)
    {
        $config['name'] = $name;
        $config['type'] = isset($config['type']) ? $config['type'] : 'field';
        $propertyType   = $config['type'];

        if (!isset($this->properties[$propertyType])) {
            throw new \RuntimeException(sprintf('Property type "%s" not found', $propertyType));
        }

        $property = $this->properties[$propertyType];
        $property->init($config);

        return $property;
    }
}
