<?php

namespace Akeneo\Component\StorageUtils\Repository;

/**
 * Searchable repository interface
 *
 * @author    Willy Mesnage <willy.mesnage@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
interface SearchableRepositoryInterface
{
    /**
     * Returns an array of objects which code or label has been filtered by a search.
     * Typically, this can be used with a paginated select2 to avoid to hydrate all
     * the objects of a repository.
     *
     * @param string $search
     * @param array  $options
     *
     * @return array
     */
    public function findBySearch($search = null, array $options = []);
}
