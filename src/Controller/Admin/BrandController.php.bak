<?php

declare(strict_types=1);

namespace Rika\SyliusBrandPlugin\Controller\Admin;

use Rika\SyliusBrandPlugin\Entity\Brand;
use Rika\SyliusBrandPlugin\Form\Type\BrandType;
use Rika\SyliusBrandPlugin\Repository\BrandRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/brands')]
final class BrandController extends AbstractController
{
    public function __construct(
        private BrandRepositoryInterface $brandRepository,
        private EntityManagerInterface $entityManager,
        private SluggerInterface $slugger
    ) {}

    #[Route('/', name: 'admin_brand_index')]
    public function index(): Response
    {
        $brands = $this->brandRepository->findAll();

        return $this->render('@RikaSyliusBrandPlugin/Admin/Brand/index.html.twig', [
            'brands' => $brands,
        ]);
    }

    #[Route('/create', name: 'admin_brand_create')]
    public function create(Request $request): Response
    {
        $brand = new Brand();

        // Définir la locale courante pour les traductions
        $locale = $request->getLocale();
        $brand->setCurrentLocale($locale);
        $brand->setFallbackLocale($locale);

        $form = $this->createForm(BrandType::class, $brand);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion upload logo
            $logoFile = $form->get('logoPath')->getData();
            if ($logoFile) {
                $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$logoFile->guessExtension();

                try {
                    $logoFile->move(
                        $this->getParameter('brands_logo_directory'), // à définir dans config/services.yaml
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Impossible d’uploader le logo.');
                }

                $brand->setLogoPath($newFilename);
            }

            $this->entityManager->persist($brand);
            $this->entityManager->flush();

            $this->addFlash('success', 'La marque a été créée avec succès.');

            return $this->redirectToRoute('admin_brand_index');
        }

        return $this->render('@RikaSyliusBrandPlugin/Admin/Brand/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_brand_edit')]
    public function edit(Request $request, Brand $brand): Response
    {
        
        // Définir la locale courante pour les traductions
        $locale = $request->getLocale();
        $brand->setCurrentLocale($locale);
        $brand->setFallbackLocale($locale);

        $form = $this->createForm(BrandType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion upload logo
            $logoFile = $form->get('logoPath')->getData();
            if ($logoFile) {
                $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$logoFile->guessExtension();

                try {
                    $logoFile->move(
                        $this->getParameter('brands_logo_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Impossible d’uploader le logo.');
                }

                $brand->setLogoPath($newFilename);
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'La marque a été mise à jour avec succès.');

            return $this->redirectToRoute('admin_brand_index');
        }

        return $this->render('@RikaSyliusBrandPlugin/Admin/Brand/edit.html.twig', [
            'form' => $form->createView(),
            'brand' => $brand,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_brand_delete', methods: ['POST'])]
    public function delete(Request $request, Brand $brand): Response
    {
        if ($this->isCsrfTokenValid('delete'.$brand->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($brand);
            $this->entityManager->flush();

            $this->addFlash('success', 'La marque a été supprimée avec succès.');
        }

        return $this->redirectToRoute('admin_brand_index');
    }
}
