<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthController extends Controller
{
    public function login()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!empty($_SESSION['product_authenticated'])) {
            redirect('products');
            return;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim((string) ($_POST['username'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');
            $expected_username = getenv('PRODUCTS_ADMIN_USERNAME') ?: 'admin';
            $password_hash = getenv('PRODUCTS_ADMIN_PASSWORD_HASH') ?: '';
            $plain_password = getenv('PRODUCTS_ADMIN_PASSWORD') ?: 'admin123';
            $valid_password = $password_hash !== ''
                ? password_verify($password, $password_hash)
                : hash_equals($plain_password, $password);

            if (hash_equals($expected_username, $username) && $valid_password) {
                session_regenerate_id(true);
                $_SESSION['product_authenticated'] = true;
                $_SESSION['product_username'] = $username;
                redirect('products');
                return;
            }

            $error = 'Invalid username or password.';
        }

        $this->call->view('product_login', ['error' => $error]);
    }

    public function logout()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        unset($_SESSION['product_authenticated'], $_SESSION['product_username']);
        redirect('login');
    }
}
