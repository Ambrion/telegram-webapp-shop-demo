<?php

declare(strict_types=1);

namespace App\Admin\Presentation\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_admin_dashboard', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('admin/dashboard/index.html.twig');
    }
}
