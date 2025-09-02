<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Rika\SyliusBrandPlugin\Entity\Brand;
use Symfony\Component\String\Slugger\SluggerInterface;

final class BrandSlugListener
{
    public function __construct(
        private SluggerInterface $slugger
    ) {}

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Brand) {
            return;
        }

        $this->generateSlugs($entity);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Brand) {
            return;
        }

        $this->generateSlugs($entity);
    }

    private function generateSlugs(Brand $brand): void
    {
        foreach ($brand->getTranslations() as $translation) {
            if (!$translation->getSlug() && $translation->getName()) {
                $slug = $this->slugger->slug($translation->getName())->lower();
                $translation->setSlug($slug);
            }
        }
    }
}
