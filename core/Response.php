<?php
// core/Response.php
namespace Core;

class Response
{
    public function redirect(string $url): never
    {
        if (!str_starts_with($url, 'http')) {
            $url = APP_URL . '/' . ltrim($url, '/');
        }
        header('Location: ' . $url);
        exit;
    }

    public function json(array $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function render(string $view, array $data = [], string $layout = 'main'): void
    {
        // Ekstrak data ke variabel
        extract($data);

        // Buffer view
        ob_start();
        $viewFile = APP_PATH . '/Views/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewFile)) {
            $this->render404();
            return;
        }
        require $viewFile;
        $content = ob_get_clean();

        // Render layout
        $layoutFile = APP_PATH . '/Views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    public function render404(): never
    {
        http_response_code(404);
        echo '<div style="text-align:center;padding:60px;font-family:sans-serif">
            <h1>404</h1><p>Halaman tidak ditemukan.</p>
            <a href="' . APP_URL . '">Kembali ke Beranda</a>
        </div>';
        exit;
    }

    public function setFlash(string $type, string $message): void
    {
        Session::set('flash_' . $type, $message);
    }

    public function getFlash(string $type): ?string
    {
        $msg = Session::get('flash_' . $type);
        Session::delete('flash_' . $type);
        return $msg;
    }
}
