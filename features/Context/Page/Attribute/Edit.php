<?php

namespace Context\Page\Attribute;

/**
 * Attribute edit page
 *
 * @author    Gildas Quéméner <gildas@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Edit extends Creation
{
    /** @var string */
    protected $path = '/configuration/attribute/{id}/edit';

    /**
     * {@inheritdoc}
     */
    public function __construct($session, $pageFactory, $parameters = [])
    {
        parent::__construct($session, $pageFactory, $parameters);

        $this->elements = array_merge(
            $this->elements,
            [
                'Attribute creation' => ['css' => '.navbar-content .created'],
                'Attribute update'   => ['css' => '.navbar-content .updated'],
            ]
        );
    }
}
