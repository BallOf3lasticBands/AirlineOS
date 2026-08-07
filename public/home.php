<?php
require_once __DIR__ . '/../includes/config.php';
$require_login = true; // protect this page for logged-in users
require_once __DIR__ . '/../includes/auth.php';

$userid = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id={$userid} LIMIT 1";
$result = mysqli_query($db_connect, $query);

$page_title = 'Home';
require_once __DIR__ . '/../includes/header.php';
?>
<?php
// If a module exposes a render function for the public index, call it.
if (function_exists('render_test_module_box')) {
    echo render_test_module_box();
}
?>
<!--
This is the home page for logged in users/customers without admin rights
-->
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Home</h2>
            <?php while ($accessgroup_data = mysqli_fetch_assoc($result)): ?>
                <p>Welcome back <?= htmlspecialchars($accessgroup_data['firstname'])?> <?=htmlspecialchars($accessgroup_data['lastname'])?>!</p>
            <?php endwhile; ?>
        </div>
    </div>
    <div class="col-md-12">
        <h3>Your flights</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Departure</th>
                    <th scope="col">Arrival</th>
                    <th scope="col">Airline</th>
                    <th scope="col">Seat</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($accessgroup_data = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <th scope="row"><?= htmlspecialchars($accessgroup_data['id']) ?></th>
                            <td><?= htmlspecialchars($accessgroup_data['firstname']) ?></td>
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
    <?php if ($result) { mysqli_free_result($result); } ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';
