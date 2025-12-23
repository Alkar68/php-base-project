<?php

namespace app\Controllers;

use app\Abstract\AbstractController;
use app\Core\Logger;
use app\Core\Route;
use app\Core\Session;
use app\Helpers\SecurityHelper;
use app\Repositories\UserRepository;
use app\Entities\User;

class AuthController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    #[Route('/login', 'GET', 'login')]
    public function showLogin(): void
    {
        if (Session::has('user_id')) {
            $this->redirect('/dashboard');
        }

        $this->view->setLayout('auth');
        $this->render('auth/login', ['title' => 'Connexion']);
    }

    #[Route('/login', 'POST', 'login.post')]
    public function login(): void
    {
        $email = $this->getPost('email');
        $password = $this->getPost('password');
        $csrfToken = $this->getPost('csrf_token');

        Logger::getInstance()->info('Tentative de connexion', ['email' => $email]);

        if (!$this->verifyCsrfToken($csrfToken)) {
            Logger::getInstance()->error('Erreur CSRF', ['user_id' => Session::get('user_id')]);
            Session::set('error', 'Token CSRF invalide');
            $this->redirect('/login');
        }

        if (!$email || !$password) {
            Session::set('error', 'Veuillez remplir tous les champs');
            $this->redirect('/login');
        }

        $user = $this->userRepository->findByEmail($email);

        if (!$user || !SecurityHelper::verifyPassword($password, $user->getPassword())) {
            Logger::getInstance()->warning('Échec de connexion', ['email' => $email]);
            Session::set('error', 'Identifiants incorrects');
            $this->redirect('/login');
        }

        if (!$user->isActive()) {
            Logger::getInstance()->warning('Échec de connexion, compte désactivé', ['email' => $email]);
            Session::set('error', 'Votre compte est désactivé');
            $this->redirect('/login');
        }

        Session::regenerate();
        Session::set('user_id', $user->getId());
        Session::set('user_role', $user->getRoleId());
        Session::set('user_email', $user->getEmail());
        Session::set('user_role_id', $user->getRoleId());

        $this->userRepository->updateLastLogin($user->getId());

        $this->redirect('/dashboard');
    }

    #[Route('/register', 'GET', 'register')]
    public function showRegister(): void
    {
        if (Session::has('user_id')) {
            $this->redirect('/dashboard');
        }

        $this->view->setLayout('auth');
        $this->render('auth/register', ['title' => 'Inscription']);
    }

    #[Route('/register', 'POST', 'register.post')]
    public function register(): void
    {
        $csrfToken = $this->getPost('csrf_token');

        if (!$this->verifyCsrfToken($csrfToken)) {
            Session::set('error', 'Token CSRF invalide');
            $this->redirect('/register');
        }

        $email = SecurityHelper::sanitize($this->getPost('email'));
        $password = $this->getPost('password');
        $passwordConfirm = $this->getPost('password_confirm');
        $firstname = SecurityHelper::sanitize($this->getPost('firstname'));
        $lastname = SecurityHelper::sanitize($this->getPost('lastname'));

        // Validation
        if (!$email || !$password || !$firstname || !$lastname) {
            Session::set('error', 'Tous les champs sont obligatoires');
            $this->redirect('/register');
        }

        if (!SecurityHelper::isValidEmail($email)) {
            Session::set('error', 'Email invalide');
            $this->redirect('/register');
        }

        if (strlen($password) < 8) {
            Session::set('error', 'Le mot de passe doit contenir au moins 8 caractères');
            $this->redirect('/register');
        }

        if ($password !== $passwordConfirm) {
            Session::set('error', 'Les mots de passe ne correspondent pas');
            $this->redirect('/register');
        }

        if ($this->userRepository->emailExists($email)) {
            Session::set('error', 'Cet email est déjà utilisé');
            $this->redirect('/register');
        }

        $user = new User();
        $user->setEmail($email)
            ->setPassword(SecurityHelper::hashPassword($password))
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setRoleId(2); // ROLE_USER

        if ($this->userRepository->save($user)) {
            Session::set('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
            $this->redirect('/login');
        } else {
            Session::set('error', 'Une erreur est survenue lors de l\'inscription');
            $this->redirect('/register');
        }
    }

    #[Route('/logout', 'GET', 'logout')]
    public function logout(): void
    {
        Session::destroy();
        $this->redirect('/login');
    }

    #[Route('/forgot-password', 'GET', 'forgot-password')]
    public function showForgotPassword(): void
    {
        $this->view->setLayout('auth');
        $this->render('auth/forgot-password', [
            'title' => 'Mot de passe oublié'
        ]);
    }

    #[Route('/forgot-password', 'POST', 'forgot-password.post')]
    public function forgotPassword(): void
    {
        $csrfToken = $this->getPost('csrf_token');

        if (!$this->verifyCsrfToken($csrfToken)) {
            Session::set('error', 'Token CSRF invalide');
            $this->redirect('/forgot-password');
        }

        $email = $this->getPost('email');
        $user = $this->userRepository->findByEmail($email);

        if ($user) {
            $resetData = SecurityHelper::generatePasswordResetToken();
            $user->setPasswordResetToken($resetData['token'])
                ->setPasswordResetExpiresAt($resetData['expires_at']);

            $this->userRepository->save($user);

            // TODO: Envoyer un email avec le lien de réinitialisation
            // $resetLink = $_ENV['APP_URL'] . '/reset-password?token=' . $resetData['token'];
        }

        Session::set('success', 'Si cet email existe, un lien de réinitialisation a été envoyé');
        $this->redirect('/login');
    }

    #[Route('/reset-password', 'GET', 'reset-password')]
    public function showResetPassword(): void
    {
        $token = $this->getQuery('token');

        if (!$token) {
            $this->redirect('/login');
        }

        $this->view->setLayout('auth');
        $this->render('auth/reset-password', [
            'title' => 'Réinitialiser le mot de passe',
            'token' => $token
        ]);
    }

    #[Route('/reset-password', 'POST', 'reset-password.post')]
    public function resetPassword(): void
    {
        $csrfToken = $this->getPost('csrf_token');

        if (!$this->verifyCsrfToken($csrfToken)) {
            Session::set('error', 'Token CSRF invalide');
            $this->redirect('/login');
        }

        $token = $this->getPost('token');
        $password = $this->getPost('password');
        $passwordConfirm = $this->getPost('password_confirm');

        if (strlen($password) < 8) {
            Session::set('error', 'Le mot de passe doit contenir au moins 8 caractères');
            $this->redirect('/reset-password?token=' . $token);
        }

        if ($password !== $passwordConfirm) {
            Session::set('error', 'Les mots de passe ne correspondent pas');
            $this->redirect('/reset-password?token=' . $token);
        }

        $user = $this->userRepository->findByPasswordResetToken($token);

        if (!$user) {
            Session::set('error', 'Token invalide ou expiré');
            $this->redirect('/login');
        }

        $user->setPassword(SecurityHelper::hashPassword($password))
            ->setPasswordResetToken(null)
            ->setPasswordResetExpiresAt(null);

        $this->userRepository->save($user);

        Session::set('success', 'Mot de passe réinitialisé avec succès');
        $this->redirect('/login');
    }
}
