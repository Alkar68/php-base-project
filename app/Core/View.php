<?php

namespace app\Core;

use RuntimeException;

class View
{
    private string $layout = 'main';
    private array $data = [];

    public function render(string $view, array $data = []): string
    {
        $this->data = $data;
        extract($data, EXTR_SKIP);

        ob_start();

        // Chercher dans views/ directement (pas dans pages/)
        $viewPath = __DIR__ . '/../../views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new RuntimeException("La vue $view n'existe pas au chemin : $viewPath");
        }

        include $viewPath;
        $content = ob_get_clean();

        $layoutPath = __DIR__ . '/../../views/layouts/' . $this->layout . '.php';

        if (!file_exists($layoutPath)) {
            throw new RuntimeException("Le layout $this->layout n'existe pas");
        }

        ob_start();
        include $layoutPath;
        return ob_get_clean();
    }

    public function setLayout(string $layout): self
    {
        $this->layout = $layout;
        return $this;
    }

    // Méthode utilitaire pour accéder aux données depuis le layout
    public function getData(): array
    {
        return $this->data;
    }
}
