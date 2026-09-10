<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Products | LavaLust</title>
    <style>
        :root { --ink: #17212b; --muted: #61707d; --accent: #e05a33; --paper: #f7f2eb; --line: #ded5ca; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; color: var(--ink); background: linear-gradient(120deg, #f7f2eb, #dbe5e1); font-family: Arial, sans-serif; }
        header, main { width: min(1120px, calc(100% - 40px)); margin: auto; }
        header { display: flex; justify-content: space-between; align-items: end; gap: 20px; padding: 54px 0 28px; }
        .eyebrow { margin: 0 0 10px; color: var(--accent); font-size: 12px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; }
        h1 { margin: 0; font: 52px/.95 Georgia, serif; }
        .actions { display: flex; gap: 10px; align-items: center; }
        a, button { font: 700 12px Arial, sans-serif; letter-spacing: .06em; text-transform: uppercase; }
        a { color: var(--ink); text-decoration: none; }
        .button { padding: 12px 16px; color: white; background: var(--ink); }
        .logout { color: var(--accent); }
        .notice { margin-bottom: 18px; padding: 13px 16px; background: #e2f0e7; border-left: 3px solid #39865b; }
        section { overflow-x: auto; background: rgba(255,255,255,.84); border: 1px solid rgba(255,255,255,.8); box-shadow: 0 18px 48px rgba(23,33,43,.1); }
        table { width: 100%; min-width: 760px; border-collapse: collapse; }
        th, td { padding: 17px 18px; border-bottom: 1px solid var(--line); text-align: left; vertical-align: top; }
        th { color: var(--muted); background: #f2ece4; font-size: 11px; letter-spacing: .09em; text-transform: uppercase; }
        td { font-size: 14px; }
        td strong { display: block; margin-bottom: 4px; font-family: Georgia, serif; font-size: 18px; }
        td small { color: var(--muted); line-height: 1.4; }
        .number { white-space: nowrap; }
        .row-actions { display: flex; gap: 13px; }
        .row-actions a { color: var(--accent); }
        .empty { padding: 54px 20px; text-align: center; color: var(--muted); }
        @media (max-width: 650px) { header { display: block; } .actions { margin-top: 22px; } h1 { font-size: 42px; } }
    </style>
</head>
<body>
<header>
    <div><p class="eyebrow">Authenticated inventory</p><h1>Products</h1></div>
    <div class="actions"><a class="button" href="<?= site_url('products/create'); ?>">Add product</a><a class="logout" href="<?= site_url('logout'); ?>">Sign out</a></div>
</header>
<main>
    <?php if (!empty($flash)): ?><div class="notice"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
    <section>
        <?php if (!empty($products)): ?>
        <table>
            <thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Created</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8'); ?></strong><small><?= nl2br(htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8')); ?></small></td>
                    <td class="number">$<?= number_format((float) $product['price'], 2); ?></td>
                    <td class="number"><?= (int) $product['quantity']; ?></td>
                    <td><?= htmlspecialchars($product['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><div class="row-actions"><a href="<?= site_url('products/edit/' . (int) $product['id']); ?>">Edit</a><a href="<?= site_url('products/delete/' . (int) $product['id']); ?>" onclick="return confirm('Delete this product?');">Delete</a></div></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?><div class="empty">No products yet. Add the first item to begin.</div><?php endif; ?>
    </section>
</main>
</body>
</html>
