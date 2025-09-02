<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\EventListener;

use Rika\SyliusBrandPlugin\Entity\BrandInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;

final class BrandEventListener implements EventSubscriberInterface
{
    public function __construct(private RequestStack $requestStack, private string $uploadDir)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.brand.pre_create' => 'uploadLogo',
            'sylius.brand.pre_update' => 'uploadLogo',
        ];
    }

    public function uploadLogo(ResourceControllerEvent $event): void
    {
        /** @var BrandInterface $brand */
        $brand = $event->getSubject();
        $request = $this->requestStack->getCurrentRequest();

        $file = $request->files->get('rika_sylius_brand_plugin')['logoPath'] ?? null;

        if ($file) {
            $filename = uniqid().'.'.$file->guessExtension();
            try {
                $file->move($this->uploadDir, $filename);
                $brand->setLogoPath('/uploads/brands/'.$filename);
            } catch (FileException $e) {
                // Optionnel : loguer l'erreur
            }
        }
    }
}
