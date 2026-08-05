<?php
require_once __DIR__ . '/../../includes/config.php';
$require_admin = true; // protect this page for admin users only
require_once __DIR__ . '/../../includes/auth.php';

$query = 'SELECT * FROM customers LIMIT 200';
$result = mysqli_query($db_connect, $query);

$page_title = 'Administration';
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Customers List</h2>
            <a href="/add.php" class="btn btn-success">Add new customer</a>
        </div>
        <div class="alert alert-info">Only signed-in admins can see this page.</div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">First name</th>
                    <th scope="col">Last name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Join</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($customer_data = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <th scope="row"><?= htmlspecialchars($customer_data['customer_id']) ?></th>
                            <td><?= htmlspecialchars($customer_data['firstname']) ?></td>
                            <td><?= htmlspecialchars($customer_data['lastname']) ?></td>
                            <td><?= htmlspecialchars($customer_data['email']) ?></td>
                            <td><?= htmlspecialchars((new DateTime($customer_data['created']))->format('Y-m-d')) ?></td>
                            <td>
                                <a href="/edit.php?customer_id=<?= urlencode($customer_data['customer_id']) ?>" class="btn btn-primary">Edit</a>
                                <a href="/functions/delete.php?customer_id=<?= urlencode($customer_data['customer_id']) ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No data available!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php';
