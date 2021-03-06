<?php

namespace Pim\Bundle\FilterBundle\Form\Type;

use Pim\Component\Localization\Localizer\LocalizerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeRangeType extends AbstractType
{
    const NAME = 'pim_type_datetime_range';

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return DateRangeType::NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'field_type'    => 'datetime',
                'field_options' => [
                    'format'        => LocalizerInterface::DEFAULT_DATETIME_FORMAT,
                    'view_timezone' => null,
                ],
            ]
        );
    }
}
