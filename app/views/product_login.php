<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Admin Login</title>
    <style>
        :root { --ink: #17212b; --muted: #61707d; --accent: #e05a33; --paper: #f7f2eb; --line: #ded5ca; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; color: var(--ink); background: radial-gradient(circle at 15% 15%, #f0b08d 0, transparent 32%), linear-gradient(135deg, #f7f2eb, #dbe5e1); font-family: Georgia, serif; }
        main { width: min(430px, 100%); padding: 42px; background: rgba(255,255,255,.86); border: 1px solid rgba(255,255,255,.8); box-shadow: 0 24px 70px rgba(23,33,43,.16); }
        .eyebrow { margin: 0 0 12px; color: var(--accent); font: 700 12px/1.2 Arial, sans-serif; letter-spacing: .16em; text-transform: uppercase; }
        h1 { margin: 0 0 10px; font-size: clamp(30px, 7vw, 48px); line-height: .98; }
        .intro { margin: 0 0 28px; color: var(--muted); font: 15px/1.6 Arial, sans-serif; }
        label { display: block; margin: 18px 0 7px; font: 700 12px/1 Arial, sans-serif; letter-spacing: .08em; text-transform: uppercase; }
        input { width: 100%; padding: 13px 14px; border: 1px solid var(--line); background: #fffdf9; color: var(--ink); font: 16px Arial, sans-serif; }
        button { width: 100%; margin-top: 26px; padding: 14px; border: 0; color: #fff; background: var(--ink); cursor: pointer; font: 700 13px Arial, sans-serif; letter-spacing: .08em; text-transform: uppercase; }
        .error { padding: 11px 13px; color: #8b2d1a; background: #fce7df; border-left: 3px solid var(--accent); font: 14px/1.4 Arial, sans-serif; }
        a { display: inline-block; margin-top: 22px; color: var(--accent); font: 700 13px Arial, sans-serif; }
    </style>
</head>
<body>
<main>
    <p class="eyebrow">LavaLust / Laboratory 05</p>
    <h1>Product desk</h1>
    <p class="intro">Sign in to manage the inventory stored in your Aiven MySQL database.</p>
    <?php if (!empty($error)): ?><p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
    <form method="post" action="<?= site_url('login'); ?>">
        <label for="username">Username</label>
        <input id="username" name="username" type="text" autocomplete="username" required>
        <label for="password">Password</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required>
        <button type="submit">Sign in</button>
    </form>
    <a href="<?= site_url('/'); ?>">Return home</a>
</main>
</body>
</html>
