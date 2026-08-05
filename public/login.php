<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

// Login page should not require authentication.
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error_message = 'Please provide both email and password.';
    } else {
        $stmt = $db_connect->prepare('SELECT id, password_hash, role FROM users WHERE email = ? LIMIT 1');
        if ($stmt === false) {
            $error_message = 'Database error. Please check your database connection.';
        } else {
            $stmt->bind_param('s', $email);

            if (! $stmt->execute()) {
                $error_message = 'Database error. Please check your database connection.';
            } else {
                $stmt->bind_result($user_id, $password_hash, $user_role);
                $found = $stmt->fetch();
                $stmt->close();

                if ($found && password_verify($password, $password_hash)) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = (int) $user_id;
                    $_SESSION['role'] = $user_role ?? 'user';
                    $_SESSION['email'] = $email;

                    $redirect_to = $_SESSION['login_redirect'] ?? APP_BASE_URL;
                    unset($_SESSION['login_redirect']);

                    if (strpos($redirect_to, '/') !== 0) {
                        $redirect_to = APP_BASE_URL;
                    }

                    if ($redirect_to === '/login.php' || $redirect_to === APP_BASE_URL . 'login.php') {
                        $redirect_to = APP_BASE_URL;
                    }

                    header('Location: ' . $redirect_to);
                    exit;
                }

                $error_message = 'Invalid email or password.';
            }
        }
    }
}

$page_title = 'Login';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title mb-4">Login</h2>

                <?php if ($error_message !== ''): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
                <?php endif; ?>

                <form method="post" action="<?= htmlspecialchars(APP_BASE_URL . 'login.php') ?>">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Sign in</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';
