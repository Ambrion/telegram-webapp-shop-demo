<?php

declare(strict_types=1);

namespace App\Users\Presentation\Controller\Logout;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LogoutController extends AbstractController
{
    #[Route('/logout', name: 'app_logout')]
    public function index(Security $security): Response
    {
        // logout the user in on the current firewall
        $security->logout(false);

        return $this->redirectToRoute('home');
    }
}
