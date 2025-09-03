<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Controller\Admin;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class BrandController extends ResourceController
{
    private LocaleContextInterface $localeContext;

    public function __construct(LocaleContextInterface $localeContext)
    {
        $this->localeContext = $localeContext;
    }

    public function indexAction(Request $request): Response
    {
        // Sylius gère automatiquement la grille avec la locale courante
        return parent::indexAction($request);
    }

    public function createAction(Request $request): Response
    {
        return parent::createAction($request);
    }

    public function updateAction(Request $request): Response
    {
        return parent::updateAction($request);
    }

    public function deleteAction(Request $request): Response
    {
        return parent::deleteAction($request);
    }
}