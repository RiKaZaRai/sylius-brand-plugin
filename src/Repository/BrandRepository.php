<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Rika\SyliusBrandPlugin\Entity\BrandInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Locale\Context\LocaleContextInterface;

class BrandRepository extends EntityRepository implements BrandRepositoryInterface
{
    private LocaleContextInterface $localeContext;

    public function __construct(
        EntityManagerInterface $entityManager,
        ClassMetadata $class,
        LocaleContextInterface $localeContext
    ) {
        parent::__construct($entityManager, $class);
        $this->localeContext = $localeContext;
    }

    public function createListQueryBuilder(?string $localeCode = null): QueryBuilder
    {
        // Si aucune locale n'est fournie, on utilise la locale courante
        if (null === $localeCode) {
            try {
                $localeCode = $this->localeContext->getLocaleCode();
            } catch (\Exception $e) {
                // Fallback sur une locale par défaut si aucune n'est définie
                $localeCode = 'fr_FR'; // ou 'en_US' selon votre configuration
            }
        }

        return $this->createQueryBuilder('b')
            ->addSelect('bt')
            ->leftJoin('b.translations', 'bt', 'WITH', 'bt.locale = :localeCode')
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
