<?php

declare(strict_types=1);

namespace App\Admin\Presentation\Controller\Category;

use App\Admin\Infrastructure\Adapter\Category\CategoryAdapterInterface;
use App\Admin\Infrastructure\Adapter\Category\CategoryFilterAdapterInterface;
use App\Admin\Infrastructure\Adapter\Category\CategoryFormAdapterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/category')]
class CategoryCreateController extends AbstractController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly CategoryFormAdapterInterface $categoryFormAdapter,
        private readonly CategoryFilterAdapterInterface $categoryFilterAdapter,
        private readonly CategoryAdapterInterface $categoryAdapter,
    ) {
    }

    #[Route('/new', name: 'app_admin_category_new', methods: ['GET', 'POST'])]
    public function __invoke(Request $request): Response
    {
        $categoryDTO = $this->categoryAdapter->createEmptyCategoryDTO();

        $filter = $this->categoryFilterAdapter->createCategoryFilter();
        $filter->setIsActive(true);

        $categoryChoices = $this->categoryAdapter->findAllByCriteriaQuery($filter) ?? [];

        $form = $this->categoryFormAdapter->createForm($categoryDTO, $categoryChoices);

        $this->categoryFormAdapter->handleRequest($form, $request);

        if ($this->categoryFormAdapter->isSubmitted($form) && $this->categoryFormAdapter->isValid($form)) {
            $queryResult = $this->categoryAdapter->findCategoryByTitleQuery($categoryDTO->title);

            if (!$queryResult) {
                if (!$this->isGranted('ROLE_ADMIN')) {
                    $message = $this->translator->trans(
                        'category.created.demo.successfully',
                        [
                            '%title%' => $categoryDTO->title,
                        ],
                        'admin.category.flash.messages',
                    );

                    $this->addFlash('success', $message);

                    return $this->redirectToRoute('app_admin_category_list', [], Response::HTTP_SEE_OTHER);
                }

                $categoryId = $this->categoryAdapter->createCategory($categoryDTO);
                if ($categoryId) {
                    $title = $categoryDTO->title;

                    $message = $this->translator->trans(
                        'category.created.successfully',
                        [
                            '%title%' => $title,
                            '%id%' => $categoryId,
                            '%url%' => $this->generateUrl('app_admin_category_edit', ['categoryId' => $categoryId]),
                        ],
                        'admin.category.flash.messages',
                    );

                    $this->addFlash('success', $message);

                    return $this->redirectToRoute('app_admin_category_list', [], Response::HTTP_SEE_OTHER);
                }
            } else {
                $errorMessage = $this->translator->trans(
                    'category.creation.error',
                    [],
                    'admin.category.flash.messages',
                );
                $this->addFlash('error', $errorMessage);
            }
        }

        return $this->render('admin/category/new.html.twig', [
            'form' => $form,
        ]);
    }
}
