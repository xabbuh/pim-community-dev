<?php

namespace Akeneo\Bundle\StorageUtilsBundle\Doctrine\ORM\Repository;

use Akeneo\Component\StorageUtils\Repository\SearchableRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Searchable repository
 *
 * @author    Willy Mesnage <willy.mesnage@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class SearchableRepository implements SearchableRepositoryInterface
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var string */
    protected $entityName;

    /**
     * @param EntityManagerInterface $entityManager
     * @param string                 $entityName
     */
    public function __construct(EntityManagerInterface $entityManager, $entityName)
    {
        $this->entityManager = $entityManager;
        $this->entityName    = $entityName;
    }

    /**
     * {@inheritdoc}
     *
     * Available options:
     *      - search_by_label: if set to true, the search will be performed by code and by label. If set to false, the
     *      search will be performed only by code.
     *      - search: the search to perform
     *      - identifiers: array of specific identifiers (aka codes) to look for
     *      - limit: number of items to return
     *      - page: page to return
     */
    public function findBySearch($search = null, array $options = [])
    {
        $qb = $this->entityManager->createQueryBuilder()->select('a')->from($this->entityName, 'a');

        if (null !== $search && '' !== $search) {
            $qb->where('a.code like :search')->setParameter('search', "%$search%");
        }

        if (isset($options['search_by_label']) && $options['search_by_label']) {
            $qb->leftJoin('a.translations', 'at');
            $qb->orWhere('at.label like :search')->setParameter('search', "%$search%");
        }

        if (isset($options['identifiers']) && is_array($options['identifiers']) && !empty($options['identifiers'])) {
            $qb->andWhere('a.code in (:codes)');
            $qb->setParameter('codes', $options['identifiers']);
        }

        if (isset($options['limit'])) {
            $qb->setMaxResults((int) $options['limit']);
            if (isset($options['page'])) {
                $qb->setFirstResult((int) $options['limit'] * ((int) $options['page'] - 1));
            }
        }

        return $qb->getQuery()->getResult();
    }
}
