<?php
// Authentication helpers and page guards. Include this after config.php.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

function current_user_id(): ?int
{
    return !empty($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
}

function current_user_role(): ?string
{
    return !empty($_SESSION['role']) ? $_SESSION['role'] : null;
}

function require_login(): void
{
    if (!is_logged_in()) {
        $requestUri = $_SERVER['REQUEST_URI'] ?? APP_BASE_URL;
        $requestScript = basename($_SERVER['PHP_SELF'] ?? '');

        if ($requestScript !== 'login.php') {
            $_SESSION['login_redirect'] = $requestUri;
        }

        header('Location: ' . APP_BASE_URL . 'login.php');
        exit;
    }
}

function require_admin(): void
{
    require_login();

    if (current_user_role() !== 'admin') {
        http_response_code(403);
        echo 'Access denied.';
        exit;
    }
}

// If pages set these flags before including auth.php, enforce them automatically.
if (!empty($require_admin) && $require_admin) {
    require_admin();
} elseif (!empty($require_login) && $require_login) {
    require_login();
}
