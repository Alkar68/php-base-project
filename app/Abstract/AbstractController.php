<?php

namespace app\Abstract;

use app\Core\View;
use app\Core\Session;
use app\Helpers\SecurityHelper;

abstract class AbstractController
{
    protected View $view;

    public function __construct()
    {
        Session::start();
        $this->view = new View();
    }

    protected function render(string $view, array $data = []): void
    {
        $data['csrfToken'] = $this->generateCsrfToken();
        echo $this->view->render($view, $data);
    }

    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    protected function redirect(string $url, int $code = 302): void
    {
        http_response_code($code);
        header("Location: $url");
        exit;
    }

    protected function generateCsrfToken(): string
    {
        if (!Session::has('csrf_token')) {
            Session::set('csrf_token', SecurityHelper::generateToken());
        }

        return Session::get('csrf_token');
    }

    protected function verifyCsrfToken(string $token): bool
    {
        return hash_equals(Session::get('csrf_token', ''), $token);
    }

    protected function getPost(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    protected function getQuery(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }
}
