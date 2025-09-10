<?php

declare(strict_types=1);

namespace App\Admin\Presentation\Controller\Order;

use App\Admin\Infrastructure\Adapter\Order\OrderAdapterInterface;
use App\Admin\Infrastructure\Adapter\User\UserAdapterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/admin/order')]
class OrderViewController extends AbstractController
{
    public function __construct(
        private readonly OrderAdapterInterface $orderAdapter,
        private readonly UserAdapterInterface $userAdapter,
        private readonly TranslatorInterface $translator,
    ) {
    }

    #[Route('/{orderId}/view', name: 'app_admin_order_view', methods: ['GET'])]
    public function __invoke(Request $request, int $orderId): Response
    {
        if ($orderId <= 0) {
            $message = $this->translator->trans(
                'order.id.error',
                [],
                'admin.order.flash.messages',
            );

            throw new BadRequestHttpException($message);
        }

        $orderDTO = $this->orderAdapter->findOrderByIdQuery($orderId);

        $userDTO = $this->userAdapter->findUserByUlid($orderDTO->userUlid);

        return $this->render('admin/order/view.html.twig', [
            'order' => $orderDTO,
            'user' => $userDTO,
        ]);
    }
}
