<?php

namespace app\Core;

use Attribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use RuntimeException;

#[Attribute(Attribute::TARGET_METHOD)]
class Route
{
    public function __construct(
        public string $path,
        public string $method = 'GET',
        public ?string $name = null
    ) {}
}

class Router
{
    private array $routes = [];
    private array $namedRoutes = [];

    public function register(string $controllerClass): void
    {
        try {
            $reflection = new ReflectionClass($controllerClass);
        } catch (ReflectionException $e) {
            throw new RuntimeException('Error registering routes for controller $controllerClass: ' . $e->getMessage(), 0, $e);
        }

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $attributes = $method->getAttributes(Route::class);

            foreach ($attributes as $attribute) {
                $route = $attribute->newInstance();
                $this->routes[$route->method][$route->path] = [
                    'controller' => $controllerClass,
                    'method' => $method->getName()
                ];

                if ($route->name) {
                    $this->namedRoutes[$route->name] = $route->path;
                }
            }
        }
    }

    public function dispatch(string $uri, string $method): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);

        // Retirer le préfixe de base si présent
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
        if ($basePath && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }

        // S'assurer qu'on a au moins un "/"
        if (empty($uri)) {
            $uri = '/';
        }

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)}/', '(?P<$1>[^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                $controller = new $handler['controller']();
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                call_user_func_array([$controller, $handler['method']], $params);
                return;
            }
        }

        http_response_code(404);
        echo "404 - Page non trouvée";
    }

    public function url(string $name, array $params = []): string
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new RuntimeException("Route nommée '$name' introuvable");
        }

        $path = $this->namedRoutes[$name];
        foreach ($params as $key => $value) {
            $path = str_replace('{' . $key . '}', $value, $path);
        }

        return $_ENV['APP_URL'] . $path;
    }
}
