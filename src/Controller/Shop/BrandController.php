<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Controller\Shop;

use Rika\SyliusBrandPlugin\Repository\BrandRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BrandController extends AbstractController
{
    public function __construct(
        private BrandRepositoryInterface $brandRepository
    ) {}

    /**
     * @Route("/brands", name="shop_brand_index")
     */
    public function index(): Response
    {
        $locale = $this->getParameter('locale') ?? $this->get('request_stack')->getCurrentRequest()->getLocale();
        $brands = $this->brandRepository->findAllEnabled();

        return $this->render('@RikaSyliusBrandPlugin/Shop/Brand/index.html.twig', [
            'brands' => $brands,
            'locale' => $locale,
        ]);
    }

    /**
     * @Route("/brands/{slug}", name="shop_brand_show")
     */
    public function show(string $slug): Response
    {
        $locale = $this->getParameter('locale') ?? $this->get('request_stack')->getCurrentRequest()->getLocale();
        $brand = $this->brandRepository->findEnabledBySlug($slug, $locale);

        if (!$brand) {
            throw $this->createNotFoundException('Brand not found');
        }

        return $this->render('@RikaSyliusBrandPlugin/Shop/Brand/show.html.twig', [
            'brand' => $brand,
        ]);
    }
}
