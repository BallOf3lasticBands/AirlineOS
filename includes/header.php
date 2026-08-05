<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($page_title ?? APP_NAME) ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <?php if (!empty($extra_head)): ?>
        <?= $extra_head ?>
    <?php endif; ?>
    <link rel="stylesheet" href="/../public/assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="<?= APP_BASE_URL ?>"><?= APP_NAME ?></a>
    <div class="ml-auto">
        <?php if (function_exists('is_logged_in') && is_logged_in()): ?>
            <?php if (current_user_role() === 'admin'): ?>
                <a class="btn btn-outline-secondary btn-sm mr-2" href="<?= APP_BASE_URL ?>admin/index.php">Admin</a>
            <?php endif; ?>
            <a class="btn btn-outline-secondary btn-sm" href="<?= APP_BASE_URL ?>logout.php">Logout</a>
        <?php else: ?>
            <a class="btn btn-outline-primary btn-sm" href="<?= APP_BASE_URL ?>login.php">Login</a>
        <?php endif; ?>
    </div>
</nav>
<div class="container mt-4">
