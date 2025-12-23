<?php

namespace app\Controllers;

use app\Abstract\AbstractController;
use app\Core\Route;
use app\Core\Session;
use app\Helpers\SecurityHelper;
use app\Middleware\RoleMiddleware;
use app\Repositories\UserRepository;
use app\Repositories\RoleRepository;

class UserController extends AbstractController
{
    private UserRepository $userRepository;
    private RoleRepository $roleRepository;

    public function __construct()
    {
        parent::__construct();

        if (!Session::has('user_id')) {
            header('Location: /login');
            exit;
        }

        $this->userRepository = new UserRepository();
        $this->roleRepository = new RoleRepository();
    }

    #[Route('/users', 'GET', 'users')]
    public function index(): void
    {
        $users = $this->userRepository->findAll();

        $this->render('users/index', ['users' => $users]);
    }

    #[Route('/users/{id}', 'GET', 'users.show')]
    public function show(string $id): void
    {
        RoleMiddleware::requirePermission('users.view');

        $user = $this->userRepository->find((int)$id);

        if (!$user) {
            Session::set('error', 'Utilisateur introuvable');
            $this->redirect('/users');
        }

        $this->render('users/show', [
            'title' => 'Détails de l\'utilisateur',
            'user' => $user
        ]);
    }

    #[Route('/users/{id}/edit', 'GET', 'users.edit')]
    public function edit(string $id): void
    {
        $user = $this->userRepository->find((int)$id);

        if (!$user) {
            Session::set('error', 'Utilisateur introuvable');
            $this->redirect('/users');
        }

        $roles = $this->roleRepository->findAll();

        $this->render('users/edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    #[Route('/users/{id}/update', 'POST', 'users.update')]
    public function update(string $id): void
    {
        $csrfToken = $this->getPost('csrf_token');

        if (!$this->verifyCsrfToken($csrfToken)) {
            Session::set('error', 'Token CSRF invalide');
            $this->redirect('/users/' . $id . '/edit');
        }

        $user = $this->userRepository->find((int)$id);

        if (!$user) {
            Session::set('error', 'Utilisateur introuvable');
            $this->redirect('/users');
        }

        $email = SecurityHelper::sanitize($this->getPost('email'));
        $firstname = SecurityHelper::sanitize($this->getPost('firstname'));
        $lastname = SecurityHelper::sanitize($this->getPost('lastname'));
        $roleId = (int)$this->getPost('role_id');
        $isActive = (bool)$this->getPost('is_active');

        if ($this->userRepository->emailExists($email, $user->getId())) {
            Session::set('error', 'Cet email est déjà utilisé');
            $this->redirect('/users/' . $id . '/edit');
        }

        $user->setEmail($email)
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setRoleId($roleId)
            ->setIsActive($isActive);

        if ($this->userRepository->save($user)) {
            Session::set('success', 'Utilisateur mis à jour avec succès');
        } else {
            Session::set('error', 'Erreur lors de la mise à jour');
        }

        $this->redirect('/users');
    }

    #[Route('/users/{id}/delete', 'POST', 'users.delete')]
    public function delete(string $id): void
    {
        $csrfToken = $this->getPost('csrf_token');

        if (!$this->verifyCsrfToken($csrfToken)) {
            Session::set('error', 'Token CSRF invalide');
            $this->redirect('/users');
        }

        if ($this->userRepository->delete((int)$id)) {
            Session::set('success', 'Utilisateur supprimé avec succès');
        } else {
            Session::set('error', 'Erreur lors de la suppression');
        }

        $this->redirect('/users');
    }
}
