<?php
// src/Repository/BrandRepositoryInterface.php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Repository;

use Doctrine\ORM\QueryBuilder;
use Rika\SyliusBrandPlugin\Entity\BrandInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface BrandRepositoryInterface extends RepositoryInterface
{
    public function createListQueryBuilder(string $localeCode): QueryBuilder;
    public function findEnabledBySlug(string $slug, string $localeCode): ?BrandInterface;
    public function findAllEnabled(): array;
}
