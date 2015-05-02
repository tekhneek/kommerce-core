<?php
namespace inklabs\kommerce\EntityRepository;

use inklabs\kommerce\Entity;

class CatalogPromotion extends AbstractEntityRepository implements CatalogPromotionInterface
{
    public function getAllCatalogPromotions($queryString = null, Entity\Pagination & $pagination = null)
    {
        $qb = $this->getQueryBuilder();

        $catalogPromotions = $qb->select('catalog_promotion')
            ->from('kommerce:CatalogPromotion', 'catalog_promotion');

        if ($queryString !== null) {
            $catalogPromotions = $catalogPromotions
                ->where('catalog_promotion.name LIKE :query')
                ->setParameter('query', '%' . $queryString . '%');
        }

        $catalogPromotions = $catalogPromotions
            ->paginate($pagination)
            ->getQuery()
            ->getResult();

        return $catalogPromotions;
    }

    public function getAllCatalogPromotionsByIds($catalogPromotionIds, Entity\Pagination & $pagination = null)
    {
        $qb = $this->getQueryBuilder();

        $catalogPromotions = $qb->select('catalog_promotion')
            ->from('kommerce:CatalogPromotion', 'catalog_promotion')
            ->where('catalog_promotion.id IN (:catalogPromotionIds)')
            ->setParameter('catalogPromotionIds', $catalogPromotionIds)
            ->paginate($pagination)
            ->getQuery()
            ->getResult();

        return $catalogPromotions;
    }
}
