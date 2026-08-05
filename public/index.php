<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

$query = 'SELECT * FROM customers LIMIT 200';
$result = mysqli_query($db_connect, $query);

$page_title = 'Customer List';
require_once __DIR__ . '/../includes/header.php';
?>
<?php
// If a module exposes a render function for the public index, call it.
if (function_exists('render_test_module_box')) {
    echo render_test_module_box();
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Customers List</h2>
            <a href="add.php" class="btn btn-success">Add new customer</a>
        </div>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php include __DIR__ . '/../functions/message.php'; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"></span>
            </button>
        </div>
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
                                <a href="edit.php?customer_id=<?= urlencode($customer_data['customer_id']) ?>" class="btn btn-primary">Edit</a>
                                <a href="/../functions/delete.php?customer_id=<?= urlencode($customer_data['customer_id']) ?>" class="btn btn-danger">Delete</a>
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
        <?php if ($result) { mysqli_free_result($result); } ?>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';
