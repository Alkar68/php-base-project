<?php

namespace app\Controllers;

use app\Abstract\AbstractController;
use app\Core\Route;

class HomeController extends AbstractController
{
    #[Route('/', 'GET', 'home')]
    public function index(): void
    {
        $this->render('home/index', ['appName' => $_ENV['APP_NAME']]);
    }

    #[Route('/about', 'GET', 'about')]
    public function about(): void
    {
        $this->render('home/about', [
            'title' => 'À propos'
        ]);
    }

    #[Route('/contact', 'GET', 'contact')]
    public function contact(): void
    {
        $this->render('home/contact', [
            'title' => 'Contact'
        ]);
    }
}
