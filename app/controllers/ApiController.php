<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ApiController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->library('api');
    }

    public function health()
    {
        $this->api->respond(['status' => 'ok']);
    }

    public function login()
    {
        $this->api->require_method('POST');
        $this->api->rate_limit('login', 10, 60);
        $credentials = $this->api->body();
        $username = (string) ($credentials['username'] ?? '');
        $password = (string) ($credentials['password'] ?? '');
        $this->call->database();
        $this->call->model('UsersModel');
        $user = $username !== '' ? $this->UsersModel->find_by('username', $username) : null;
        $expected_username = getenv('PRODUCTS_ADMIN_USERNAME') ?: '';
        $password_hash = getenv('PRODUCTS_ADMIN_PASSWORD_HASH') ?: '';
        $configured_password = getenv('PRODUCTS_ADMIN_PASSWORD') ?: '';

        if (is_array($user)) {
            if (empty($user['is_active']) || !password_verify($password, (string) $user['password'])) {
                $this->api->respond_error('Invalid username or password.', 401);
            }

            $user_id = (int) $user['id'];
            $role = (string) $user['role'];
        } else {
            if ($expected_username === '' || ($password_hash === '' && $configured_password === '')) {
                $this->api->respond_error('API login is not configured.', 503);
            }

            $valid_password = $password_hash !== ''
                ? password_verify($password, $password_hash)
                : hash_equals($configured_password, $password);

            if (!hash_equals($expected_username, $username) || !$valid_password) {
                $this->api->respond_error('Invalid username or password.', 401);
            }

            $user_id = (int) (getenv('PRODUCTS_ADMIN_USER_ID') ?: 1);
            $role = 'admin';
        }

        $tokens = $this->api->issue_tokens([
            'id' => $user_id,
            'role' => $role,
            'scopes' => ['products:read', 'products:write'],
        ]);

        $this->api->respond([
            'data' => $tokens,
            'user' => ['username' => $username],
        ]);
    }

    public function register()
    {
        $this->api->require_method('POST');
        $this->api->rate_limit('register', 5, 3600);
        $input = $this->api->body();
        $username = trim((string) ($input['username'] ?? ''));
        $email = trim((string) ($input['email'] ?? ''));
        $password = (string) ($input['password'] ?? '');

        if (!preg_match('/^[A-Za-z0-9_.-]{3,100}$/', $username)) {
            $this->api->respond_error('Username must be 3 to 100 characters and use only letters, numbers, dots, underscores, or hyphens.', 422);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
            $this->api->respond_error('Enter a valid email address.', 422);
        }
        if (strlen($password) < 8) {
            $this->api->respond_error('Password must be at least 8 characters.', 422);
        }

        $this->call->database();
        $this->call->model('UsersModel');

        if ($this->UsersModel->find_by('username', $username)) {
            $this->api->respond_error('That username is already in use.', 409);
        }
        if ($this->UsersModel->find_by('email', $email)) {
            $this->api->respond_error('That email address is already in use.', 409);
        }

        $user_id = $this->UsersModel->insert([
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user',
            'is_active' => 1,
        ]);

        if (!$user_id) {
            $this->api->respond_error('Could not create the account.', 500);
        }

        $tokens = $this->api->issue_tokens([
            'id' => (int) $user_id,
            'role' => 'user',
            'scopes' => ['products:read', 'products:write'],
        ]);

        $this->api->respond([
            'data' => $tokens,
            'user' => ['id' => (int) $user_id, 'username' => $username, 'email' => $email],
        ], 201);
    }

    public function logout()
    {
        $this->api->require_method('POST');
        $this->api->require_jwt();
        $this->call->database();
        $body = $this->api->body();
        $refresh_token = (string) ($body['refresh_token'] ?? '');

        if ($refresh_token !== '') {
            $this->api->revoke_refresh_token($refresh_token);
        }

        $this->api->respond(['message' => 'Logged out.']);
    }

    public function products()
    {
        $this->api->require_jwt();
        $this->call->database();
        $this->call->model('ProductModel');
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

        if ($method === 'GET') {
            $this->api->respond(['data' => $this->ProductModel->all_products()]);
        }

        if ($method === 'POST') {
            $data = $this->validated_product($this->api->body());
            $product_id = $this->ProductModel->insert($data);
            $this->api->respond(['data' => $this->ProductModel->find((int) $product_id)], 201);
        }

        $this->api->respond_error('Method Not Allowed.', 405);
    }

    public function product($id)
    {
        $this->api->require_jwt();
        $this->call->database();
        $this->call->model('ProductModel');
        $product_id = (int) $id;
        $product = $this->ProductModel->find($product_id);

        if (!$product) {
            $this->api->respond_error('Product not found.', 404);
        }

        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

        if ($method === 'GET') {
            $this->api->respond(['data' => $product]);
        }

        if ($method === 'PUT' || $method === 'PATCH') {
            $data = $this->validated_product($this->api->body(), $product);
            $this->ProductModel->update($product_id, $data);
            $this->api->respond(['data' => $this->ProductModel->find($product_id)]);
        }

        if ($method === 'DELETE') {
            $this->ProductModel->delete($product_id);
            $this->api->respond(['message' => 'Product deleted.']);
        }

        $this->api->respond_error('Method Not Allowed.', 405);
    }

    private function validated_product(array $input, $current = [])
    {
        $data = array_merge(is_array($current) ? $current : (array) $current, $input);
        $product = [
            'product_name' => trim((string) ($data['product_name'] ?? '')),
            'description' => trim((string) ($data['description'] ?? '')),
            'price' => trim((string) ($data['price'] ?? '')),
            'quantity' => trim((string) ($data['quantity'] ?? '')),
        ];

        if ($product['product_name'] === '' || strlen($product['product_name']) > 100) {
            $this->api->respond_error('Product name is required and must be at most 100 characters.', 422);
        }
        if ($product['description'] === '') {
            $this->api->respond_error('Description is required.', 422);
        }
        if (!preg_match('/^\d{1,8}(?:\.\d{1,2})?$/', $product['price'])) {
            $this->api->respond_error('Price must be between 0 and 99999999.99 with at most two decimals.', 422);
        }
        if (filter_var($product['quantity'], FILTER_VALIDATE_INT) === false || (int) $product['quantity'] < 0) {
            $this->api->respond_error('Quantity must be a non-negative whole number.', 422);
        }

        return $product;
    }
}