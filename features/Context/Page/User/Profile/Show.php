<?php

namespace Context\Page\User\Profile;

use Context\Page\Base\Form;

/**
 * My profile edit page
 *
 * @author    Pierre Allard <pierre.allard@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Show extends Form
{
    /** @var string */
    protected $path = '/user/profile/view';

    /**
     * {@inheritdoc}
     */
    public function __construct($session, $pageFactory, $parameters = [])
    {
        parent::__construct($session, $pageFactory, $parameters);

        $this->elements = array_merge(
            [
                'User creation'   => ['css' => '.customer-info .created'],
                'User update'     => ['css' => '.customer-info .updated'],
                'User last login' => ['css' => '.customer-info .last-login'],
            ],
            $this->elements
        );
    }
}
