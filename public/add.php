<?php
require_once __DIR__ . '/../includes/config.php';
$require_login = true; // protect this page for logged-in users
require_once __DIR__ . '/../includes/auth.php';

$page_title = 'Add Customer';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="row">
    <div class="col-md-8">
        <div class="page-header mb-4">
            <h2>Add a new customer</h2>
        </div>
        <form action="/functions/add_process.php" method="post">
            <div class="form-group">
                <label>First name</label>
                <input type="text" name="firstname" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Last name</label>
                <input type="text" name="lastname" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';
