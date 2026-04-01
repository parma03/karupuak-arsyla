<?php
// core/Router.php
namespace Core;

class Router
{
    private array $routes = [];

    public function get(string $path, string $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, string $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(Request $request, Response $response): void
    {
        $method = $request->method();
        $url    = $request->url();

        foreach (($this->routes[$method] ?? []) as $route => $handler) {
            $pattern = $this->buildPattern($route);
            if (preg_match($pattern, $url, $matches)) {
                // Hapus full match
                array_shift($matches);
                $params = array_values($matches);

                [$controllerName, $action] = explode('@', $handler);

                $namespace = str_contains($controllerName, '\\')
                    ? 'App\\Controllers\\'
                    : 'App\\Controllers\\';

                // Jika ada sub-namespace Admin\
                $fullClass = 'App\\Controllers\\' . str_replace('/', '\\', $controllerName);

                if (!class_exists($fullClass)) {
                    $response->render404();
                    return;
                }

                $controller = new $fullClass();
                if (!method_exists($controller, $action)) {
                    $response->render404();
                    return;
                }

                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        $response->render404();
    }

    private function buildPattern(string $route): string
    {
        // Ganti {param} dengan regex capture group
        $pattern = preg_replace('/\{[a-z_]+\}/', '([^/]+)', $route);
        return '#^' . $pattern . '$#';
    }
}
