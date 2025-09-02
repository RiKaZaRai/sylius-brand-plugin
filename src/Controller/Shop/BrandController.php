<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Controller\Shop;

use Rika\SyliusBrandPlugin\Repository\BrandRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BrandController extends AbstractController
{
    public function __construct(
        private BrandRepositoryInterface $brandRepository,
        private LocaleContextInterface $localeContext,
        private ChannelContextInterface $channelContext
    ) {
    }

    public function indexAction(Request $request): Response
    {
        $brands = $this->brandRepository->findAllEnabled();

        return $this->render('@RikaSyliusBrandPlugin/Shop/Brand/index.html.twig', [
            'brands' => $brands,
        ]);
    }

    public function showAction(Request $request, string $slug): Response
    {
        $localeCode = $this->localeContext->getLocaleCode();
        $brand = $this->brandRepository->findEnabledBySlug($slug, $localeCode);

        if (!$brand) {
            throw $this->createNotFoundException('Brand not found');
        }

        $channel = $this->channelContext->getChannel();
        
        $products = $brand->getProducts()->filter(function ($product) use ($channel) {
            return $product->getChannels()->contains($channel) && $product->isEnabled();
        });

        return $this->render('@RikaSyliusBrandPlugin/Shop/Brand/show.html.twig', [
            'brand' => $brand,
            'products' => $products,
        ]);
    }
}
