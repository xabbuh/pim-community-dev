<?php

namespace Context\Page\Import;

use Context\Page\Job\Show as JobShow;

/**
 * Import show page
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Show extends JobShow
{
    /** @var string */
    protected $path = '/collect/import/{id}';

    /**
     * {@inheritdoc}
     */
    public function __construct($session, $pageFactory, $parameters = [])
    {
        parent::__construct($session, $pageFactory, $parameters);

        $this->elements = array_merge(
            $this->elements,
            [
                'Import now' => ['css' => '.navbar-buttons .import-btn']
            ]
        );
    }

    /**
     * Click the job execution link
     */
    public function execute()
    {
        $this->getElement('Import now')->click();
    }
}
