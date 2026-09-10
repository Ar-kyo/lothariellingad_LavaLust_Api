<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ProductController extends Controller
{
    public function index()
    {
        $this->call->database();
        $this->call->model('ProductModel');

        $this->call->view('products', [
            'products' => $this->ProductModel->all_products(),
            'flash' => $this->take_flash(),
        ]);
    }

    public function create()
    {
        $this->call->view('product_form', [
            'product' => [],
            'form_action' => 'products/store',
            'form_title' => 'Add product',
            'error' => null,
        ]);
    }

    public function store()
    {
        $data = $this->product_data();
        $error = $this->validate_product($data);
        if ($error !== null) {
            $this->call->view('product_form', [
                'product' => $data,
                'form_action' => 'products/store',
                'form_title' => 'Add product',
                'error' => $error,
            ]);
            return;
        }

        $this->call->database();
        $this->call->model('ProductModel');
        $this->ProductModel->insert($data);
        $this->set_flash('Product added successfully.');
        redirect('products');
    }

    public function edit($id)
    {
        $this->call->database();
        $this->call->model('ProductModel');
        $product = $this->ProductModel->find((int) $id);
        if (empty($product)) {
            redirect('products');
            return;
        }

        $this->call->view('product_form', [
            'product' => $product,
            'form_action' => 'products/update/' . (int) $id,
            'form_title' => 'Edit product',
            'error' => null,
        ]);
    }

    public function update($id)
    {
        $product_id = (int) $id;
        $data = $this->product_data();
        $error = $this->validate_product($data);
        if ($error !== null) {
            $data['id'] = $product_id;
            $this->call->view('product_form', [
                'product' => $data,
                'form_action' => 'products/update/' . $product_id,
                'form_title' => 'Edit product',
                'error' => $error,
            ]);
            return;
        }

        $this->call->database();
        $this->call->model('ProductModel');
        $this->ProductModel->update($product_id, $data);
        $this->set_flash('Product updated successfully.');
        redirect('products');
    }

    public function delete($id)
    {
        $this->call->database();
        $this->call->model('ProductModel');
        $this->ProductModel->delete((int) $id);
        $this->set_flash('Product deleted successfully.');
        redirect('products');
    }

    private function product_data()
    {
        return [
            'product_name' => trim((string) ($_POST['product_name'] ?? '')),
            'description' => trim((string) ($_POST['description'] ?? '')),
            'price' => trim((string) ($_POST['price'] ?? '')),
            'quantity' => trim((string) ($_POST['quantity'] ?? '')),
        ];
    }

    private function validate_product($data)
    {
        if ($data['product_name'] === '' || strlen($data['product_name']) > 100) {
            return 'Product name is required and must be 100 characters or fewer.';
        }
        if ($data['description'] === '') {
            return 'Description is required.';
        }
        if (!is_numeric($data['price']) || (float) $data['price'] < 0) {
            return 'Price must be a non-negative number.';
        }
        if (filter_var($data['quantity'], FILTER_VALIDATE_INT) === false || (int) $data['quantity'] < 0) {
            return 'Quantity must be a non-negative whole number.';
        }

        return null;
    }

    private function set_flash($message)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['product_flash'] = $message;
    }

    private function take_flash()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $message = $_SESSION['product_flash'] ?? null;
        unset($_SESSION['product_flash']);
        return $message;
    }
}
