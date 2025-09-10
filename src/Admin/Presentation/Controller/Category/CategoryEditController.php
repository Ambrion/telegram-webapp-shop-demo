<?php

declare(strict_types=1);

namespace App\Admin\Presentation\Controller\Category;

use App\Admin\Domain\Exception\CategoryNotFoundException;
use App\Admin\Infrastructure\Adapter\Category\CategoryAdapterInterface;
use App\Admin\Infrastructure\Adapter\Category\CategoryFilterAdapterInterface;
use App\Admin\Infrastructure\Adapter\Category\CategoryFormAdapterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/category')]
class CategoryEditController extends AbstractController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly CategoryFormAdapterInterface $categoryFormAdapter,
        private readonly CategoryFilterAdapterInterface $categoryFilterAdapter,
        private readonly CategoryAdapterInterface $categoryAdapter,
    ) {
    }

    #[Route('/{categoryId}/edit', name: 'app_admin_category_edit', methods: ['GET', 'POST'])]
    public function __invoke(Request $request, int $categoryId): Response
    {
        if ($categoryId <= 0) {
            $message = $this->translator->trans(
                'category.id.error',
                [],
                'admin.category.flash.messages',
            );

            throw new BadRequestHttpException($message);
        }

        $categoryDTO = $this->categoryAdapter->findCategoryByIdQuery($categoryId);
        if (null === $categoryDTO) {
            throw new CategoryNotFoundException($categoryId);
        }

        $filter = $this->categoryFilterAdapter->createCategoryFilter();
        $filter->setIsActive(true);

        $categoryChoices = $this->categoryAdapter->findAllByCriteriaQuery($filter) ?? [];

        $form = $this->categoryFormAdapter->createForm($categoryDTO, $categoryChoices);

        $this->categoryFormAdapter->handleRequest($form, $request);

        if ($this->categoryFormAdapter->isSubmitted($form) && $this->categoryFormAdapter->isValid($form)) {
            $queryResult = $this->categoryAdapter->findCategoryByTitleQuery($categoryDTO->title);

            if (!$queryResult || ($queryResult->id === $categoryDTO->id)) {
                if (!$this->isGranted('ROLE_ADMIN')) {
                    return $this->redirectAfterSuccess($categoryDTO->title, $categoryId);
                }

                $result = $this->categoryAdapter->updateCategory($categoryDTO);
                if ($result) {
                    return $this->redirectAfterSuccess($categoryDTO->title, $categoryId);
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

        return $this->render('admin/category/edit.html.twig', [
            'form' => $form,
            'parentId' => $categoryDTO->parentId,
        ]);
    }

    private function redirectAfterSuccess(string $title, int $categoryId): Response
    {
        $message = $this->translator->trans(
            'category.updated.successfully',
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
}
