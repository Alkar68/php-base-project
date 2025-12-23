<?php

namespace app\Controllers;

use app\Abstract\AbstractController;
use app\Core\Route;
use app\Core\Session;
use app\Repositories\UserRepository;

class DashboardController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();

        if (!Session::has('user_id')) {
            header('Location: /login');
            exit;
        }

        $this->userRepository = new UserRepository();
    }

    #[Route('/dashboard', 'GET', 'dashboard')]
    public function index(): void
    {
        $user = $this->userRepository->find(Session::get('user_id'));
        $totalUsers = $this->userRepository->count();

        $this->render('dashboard/index', [
            'user' => $user,
            'totalUsers' => $totalUsers
        ]);
    }
}
