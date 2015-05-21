<?php

namespace Pim\Component\Connector\ArrayConverter\Flat\Product\Converter;

/**
 * Registry of converters.
 *
 * @author    Olivier Soulet <olivier.soulet@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface ValueConverterRegistryInterface
{
    /**
     * Register a converter.
     *
     * @param ValueConverterInterface $converter
     *
     * @return ValueConverterRegistry
     */
    public function register(ValueConverterInterface $converter);

    /**
     * @param string $attributeType
     *
     * @return ValueConverterInterface
     */
    public function getConverter($attributeType);
}