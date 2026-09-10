<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($form_title, ENT_QUOTES, 'UTF-8'); ?> | LavaLust</title>
    <style>
        :root { --ink: #17212b; --muted: #61707d; --accent: #e05a33; --line: #ded5ca; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; padding: 42px 20px; color: var(--ink); background: linear-gradient(120deg, #f7f2eb, #dbe5e1); font-family: Arial, sans-serif; }
        main { width: min(700px, 100%); margin: auto; padding: 38px; background: rgba(255,255,255,.87); box-shadow: 0 18px 48px rgba(23,33,43,.1); }
        .eyebrow { margin: 0 0 10px; color: var(--accent); font-size: 12px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; }
        h1 { margin: 0 0 28px; font: 46px/.98 Georgia, serif; }
        .error { padding: 12px 14px; color: #8b2d1a; background: #fce7df; border-left: 3px solid var(--accent); }
        label { display: block; margin: 19px 0 7px; font-size: 12px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
        input, textarea { width: 100%; padding: 12px 13px; border: 1px solid var(--line); background: #fffdf9; color: var(--ink); font: 16px Arial, sans-serif; }
        textarea { min-height: 130px; resize: vertical; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        .buttons { display: flex; align-items: center; gap: 18px; margin-top: 28px; }
        button { padding: 13px 18px; border: 0; color: #fff; background: var(--ink); cursor: pointer; font: 700 12px Arial, sans-serif; letter-spacing: .06em; text-transform: uppercase; }
        a { color: var(--accent); font-size: 13px; font-weight: 700; text-decoration: none; }
        @media (max-width: 560px) { main { padding: 27px 22px; } .grid { grid-template-columns: 1fr; gap: 0; } h1 { font-size: 39px; } }
    </style>
</head>
<body>
<main>
    <p class="eyebrow">Product management</p>
    <h1><?= htmlspecialchars($form_title, ENT_QUOTES, 'UTF-8'); ?></h1>
    <?php if (!empty($error)): ?><p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
    <form method="post" action="<?= site_url($form_action); ?>">
        <label for="product_name">Product name</label>
        <input id="product_name" name="product_name" maxlength="100" value="<?= htmlspecialchars($product['product_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
        <label for="description">Description</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($product['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
        <div class="grid">
            <div><label for="price">Price</label><input id="price" name="price" type="number" min="0" step="0.01" value="<?= htmlspecialchars($product['price'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></div>
            <div><label for="quantity">Quantity</label><input id="quantity" name="quantity" type="number" min="0" step="1" value="<?= htmlspecialchars($product['quantity'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></div>
        </div>
        <div class="buttons"><button type="submit">Save product</button><a href="<?= site_url('products'); ?>">Cancel</a></div>
    </form>
</main>
</body>
</html>
