<?php

namespace Pim\Bundle\CatalogBundle\Factory;

use Pim\Bundle\CatalogBundle\Entity\AttributeRequirement;
use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Model\AttributeRequirementInterface;
use Pim\Component\Catalog\Model\ChannelInterface;

/**
 * Attribute requirement factory
 *
 * @author    Gildas Quemener <gildas@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AttributeRequirementFactory
{
    /**
     * Create and configure an attribute requirement instance
     *
     * @param AttributeInterface $attribute
     * @param ChannelInterface   $channel
     * @param bool               $required
     *
     * @return AttributeRequirementInterface
     */
    public function createAttributeRequirement(AttributeInterface $attribute, ChannelInterface $channel, $required)
    {
        $requirement = new AttributeRequirement();
        $requirement->setAttribute($attribute);
        $requirement->setChannel($channel);
        $requirement->setRequired($required);

        return $requirement;
    }
}
