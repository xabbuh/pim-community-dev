<?php

namespace Pim\Bundle\EnrichBundle\Doctrine\ORM\Repository;

use Akeneo\Component\StorageUtils\Repository\SearchableRepositoryInterface;

/**
 * @author    Adrien Pétremann <adrien.petremann@akeneo.com>
 * @copyright 2016 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class SearchableRepositoryRegistry
{
    /** @var SearchableRepositoryInterface[] */
    protected $repositories = [];

    /**
     * Register a searchable repository
     *
     * @param string                        $alias
     * @param SearchableRepositoryInterface $repository
     *
     * @return SearchableRepositoryRegistry
     */
    public function register($alias, SearchableRepositoryInterface $repository)
    {
        $this->repositories[$alias] = $repository;

        return $this;
    }

    /**
     * Return the searchable repository
     *
     * @param string $alias
     *
     * @throws \LogicException
     *
     * @return SearchableRepositoryInterface
     */
    public function get($alias)
    {
        if (!isset($this->repositories[$alias])) {
            throw new \LogicException(sprintf('Searchable repository "%s" is not registered', $alias));
        }

        return $this->repositories[$alias];
    }

    /**
     * Return the searchable repositories aliases
     *
     * @return array
     */
    public function getAliases()
    {
        return array_keys($this->repositories);
    }
}
