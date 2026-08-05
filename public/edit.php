<?php
require_once __DIR__ . '/../includes/config.php';
$require_login = true; // protect this page for logged-in users
require_once __DIR__ . '/../includes/auth.php';

$customer_id = isset($_GET['customer_id']) ? (int) $_GET['customer_id'] : 0;

$query = "SELECT * FROM customers WHERE customer_id = {$customer_id}";
$query_res = mysqli_query($db_connect, $query);
$customer = mysqli_fetch_assoc($query_res);

$page_title = 'Edit Customer';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="row">
    <div class="col-md-8">
        <div class="page-header mb-4">
            <h2>Edit customer</h2>
        </div>
        <form action="/functions/edit_process.php" method="POST">
            <input type="hidden" name="customer_id" value="<?= htmlspecialchars($customer_id) ?>" class="form-control" required>
            <div class="form-group">
                <label for="firstname">First name</label>
                <input id="firstname" type="text" name="firstname" class="form-control" value="<?= htmlspecialchars($customer['firstname'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="lastname">Last name</label>
                <input id="lastname" type="text" name="lastname" class="form-control" value="<?= htmlspecialchars($customer['lastname'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" class="form-control" value="<?= htmlspecialchars($customer['email'] ?? '') ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';
