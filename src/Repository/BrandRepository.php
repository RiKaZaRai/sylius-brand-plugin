<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Rika\SyliusBrandPlugin\Entity\BrandInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class BrandRepository extends EntityRepository implements BrandRepositoryInterface
{
    public function createListQueryBuilder(string $localeCode): QueryBuilder
    {
        return $this->createQueryBuilder('b')
            ->addSelect('bt')
            ->leftJoin('b.translations', 'bt')
            ->andWhere('bt.locale = :localeCode OR bt.locale IS NULL')
            ->setParameter('localeCode', $localeCode)
            ->orderBy('b.position', 'ASC')
            ->addOrderBy('b.code', 'ASC')
        ;
    }

    public function findEnabledBySlug(string $slug, string $localeCode): ?BrandInterface
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.translations', 'bt')
            ->andWhere('bt.slug = :slug')
            ->andWhere('bt.locale = :localeCode')
            ->andWhere('b.enabled = true')
            ->setParameter('slug', $slug)
            ->setParameter('localeCode', $localeCode)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findAllEnabled(): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.enabled = true')
            ->orderBy('b.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}