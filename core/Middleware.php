<?php
// core/Middleware.php
namespace Core;

class Middleware
{
    /**
     * Pastikan user sudah login (customer)
     */
    public static function auth(): void
    {
        if (!Session::isLoggedIn()) {
            Session::set('intended_url', $_SERVER['REQUEST_URI'] ?? '/');
            $response = new Response();
            $response->setFlash('warning', 'Silakan login terlebih dahulu.');
            $response->redirect('login');
        }
    }

    /**
     * Pastikan user adalah admin atau owner
     */
    public static function admin(): void
    {
        if (!Session::isLoggedIn() || !Session::isAdmin()) {
            $response = new Response();
            $response->redirect('admin/login');
        }
    }

    /**
     * Pastikan user adalah owner
     */
    public static function owner(): void
    {
        if (!Session::isLoggedIn() || !Session::isOwner()) {
            $response = new Response();
            $response->redirect('admin');
        }
    }

    /**
     * Pastikan user belum login (untuk halaman login/register)
     */
    public static function guest(): void
    {
        if (Session::isLoggedIn()) {
            $role = Session::userRole();
            $response = new Response();
            if ($role === 'customer') {
                $response->redirect('');
            } else {
                $response->redirect('admin');
            }
        }
    }
}
