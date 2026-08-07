<?php
require_once __DIR__ . '/../../includes/config.php';
$require_admin = true; // protect this page for admin users only
require_once __DIR__ . '/../../includes/auth.php';


$user_query = "
    SELECT
        us.id,
        us.firstname,
        us.lastname,
        us.email,
        us.role,
        us.created_at,
        GROUP_CONCAT(
            COALESCE(
                NULLIF(ag.groupname, ''),
                CAST(ag.id AS CHAR)
            )
            SEPARATOR ', '
        ) AS memberships
    FROM users us
    LEFT JOIN accessgroups_members agm ON agm.user_id = us.id
    LEFT JOIN accessgroups ag ON ag.id = agm.accessgroup_id
    GROUP BY us.id, us.firstname, us.lastname, us.email, us.role, us.created_at
    ORDER BY us.id ASC
    LIMIT 200
";

/*
$user_query = "
    SELECT
        u.id,
        u.firstname,
        u.lastname,
        u.email,
        u.role,
        u.created_at
    FROM users u
    LIMIT 200
";*/
$user_result = mysqli_query($db_connect, $user_query);

$page_title = 'Users List';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Users List</h2>
            <a href="add.php" class="btn btn-success">Add new user</a>
        </div>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php include __DIR__ . '/../../functions/message.php'; ?>
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
                    <th scope="col">Group</th>
                    <th scope="col">Admin</th>
                    <th scope="col">Created</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($user_result && $user_result->num_rows > 0): ?>
                    <?php while ($users_data = mysqli_fetch_assoc($user_result)): ?>
                        <tr>
                            <th scope="row"><?= htmlspecialchars($users_data['id']) ?></th>
                            <td><?= htmlspecialchars($users_data['firstname']) ?></td>
                            <td><?= htmlspecialchars($users_data['lastname']) ?></td>
                            <td><?= htmlspecialchars($users_data['email']) ?></td>
                            <td><?= htmlspecialchars(!empty($users_data['memberships']) ? $users_data['memberships'] : 'No memberships assigned') ?></td>
                            <td><?= htmlspecialchars($users_data['role']) ?></td>
                            <td><?= htmlspecialchars((new DateTime($users_data['created']))->format('Y-m-d')) ?></td>
                            <td>
                                <a href="/../edit.php?customer_id=<?= urlencode($users_data['customer_id']) ?>" class="btn btn-primary">Edit</a>
                                <?php if ($active): ?>
                                    <a href="?action=deactivate&slug=<?= urlencode($slug) ?>" class="btn btn-warning">Deactivate</a>
                                <?php else: ?>
                                    <a href="?action=activate&slug=<?= urlencode($slug) ?>" class="btn btn-success">Activate</a>
                                <?php endif; ?>                            
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
        <?php if ($user_result) { mysqli_free_result($user_result); } ?>
    </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php';
