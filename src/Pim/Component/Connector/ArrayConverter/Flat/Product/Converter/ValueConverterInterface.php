<?php

namespace Pim\Component\Connector\ArrayConverter\Flat\Product\Converter;

/**
 * Converts data.
 *
 * @author    Olivier Soulet <olivier.soulet@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface ValueConverterInterface
{
    /**
     * Converts value.
     *
     * @param string $attributeFieldInfo
     * @param string $value
     *
     * @return array
     */
    public function convert($attributeFieldInfo, $value);

    /**
     * Supports the field.
     *
     * @param string $attributeType
     *
     * @return bool
     */
    public function supportsField($attributeType);
}