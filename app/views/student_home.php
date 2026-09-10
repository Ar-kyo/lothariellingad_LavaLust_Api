<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #eef6f7; color: #173b42; margin: 0; padding: 48px 20px; }
        .card { max-width: 680px; margin: auto; background: #fff; padding: 36px; border-radius: 14px; box-shadow: 0 8px 24px rgba(23, 59, 66, .12); }
        h1 { margin-top: 0; color: #0d6875; }
        nav { margin-top: 28px; }
        a { color: #0d6875; font-weight: 700; margin-right: 18px; }
    </style>
</head>
<body>
    <main class="card">
        <h1><?= htmlspecialchars($welcome_message, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p>Welcome to my Student Information Portal.</p>
        <nav>
            <a href="<?= site_url('student'); ?>">Home</a>
            <a href="<?= site_url('student/profile'); ?>">Student Profile</a>
            <a href="<?= site_url('users'); ?>">Users</a>
            <a href="<?= site_url('products'); ?>">Products</a>
        </nav>
    </main>
</body>
</html>